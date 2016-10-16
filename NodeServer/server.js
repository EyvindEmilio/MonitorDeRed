var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
var mysql = require('mysql');
var fs = require("fs");
var watch = require('node-watch');
var iftop = require('./iftop');
var nmap_ports = require('./nmap_ports');
var statistics = require('./statistics');
var tcpdump = require('./tcpdump');
var net_discover = require('./net_discover');
var mail = require('./send_mail');
var snmp = require('./snmp');

var ENV = [];
var connection = null;
var number_clients = 0;
var SETTINGS = {};
var INTERVAL_SEND_DATA_MONITORING = 1;//default 1 seg

fs.readFile('../.env', 'utf-8', function (err, data) {
    var TMP = data.match(/[A-Z_]{1,100}=[a-zA-Z0-9.,_$:=/ ]{0,100}/g);
    if (TMP == null) return 0;
    ENV = [];
    for (var i = 0; i < TMP.length; i++) {
        var conf = TMP[i].split('=');
        ENV[conf[0]] = conf[1];
    }
    start_app();
});

function start_app() {
    connection = mysql.createConnection({
        host: ENV['DB_HOST'],
        user: ENV['DB_USERNAME'],
        password: ENV['DB_PASSWORD'],
        database: ENV['DB_DATABASE']
    });

    connection.connect();
    connection.query('SELECT * from settings', function (err, rows) {
        if (err) throw err;
        SETTINGS = rows[0];
        SETTINGS.connection = connection;//assign connection for other modules
        INTERVAL_SEND_DATA_MONITORING = parseInt(SETTINGS['time_interval_for_sending_monitoring_data']);

        server.listen(8890);

        start_scan_network();
        start_bandwidth();
        start_nmap_ports();
        start_statistics();
        start_denial_service();
        start_snmp();
        start_saturation();
    });

}

function send_data_scan(list_device_capture) {
    connection.query('SELECT devices.name,devices.ip, areas.name AS area,device_types.name AS device_type from devices INNER JOIN areas on areas.id = devices.area INNER JOIN device_types on device_types.id = devices.device_type WHERE devices.status = "Y"', function (err, rows) {
        if (err) throw err;
        var i, j, exist;
        for (i = 0; i < list_device_capture.length; i++) {
            list_device_capture[i].name = '-- Desconocido --';
            for (j = 0; j < rows.length; j++) {
                if (list_device_capture[i].ip == rows[j].ip) {
                    list_device_capture[i].name = rows[j].name;
                    list_device_capture[i].area = rows[j].area;
                    list_device_capture[i].device_type = rows[j].device_type;
                }
            }
            list_device_capture[i].status_network = 'Y';
        }
        for (j = 0; j < rows.length; j++) {
            exist = false;
            rows[j].status_network = 'N';
            for (i = 0; i < list_device_capture.length; i++) {
                if (list_device_capture[i].ip == rows[j].ip) exist = true;
            }
            if (!exist) list_device_capture.push(rows[j]);
        }

        list_device_capture.sort(function (a, b) {
            if (a.name < b.name) return 1;
            if (a.name > b.name) return -1;
            return 0;
        });
        if (number_clients > 0) {
            io.sockets.emit('active_pcs', {date: new Date(), data: list_device_capture});
            var pc_inactive = 0;
            var text = "";
            for (var index = 0; index < list_device_capture.length; index++) {
                if (list_device_capture[index].name != '-- Desconocido --' && list_device_capture[index].status_network == 'N') {
                    pc_inactive++;
                    text += 'IP:' + list_device_capture[index]['ip'] + ', Tipo:' + list_device_capture[index]['device_type'] + ', Nombre:' + list_device_capture[index]['name'] + ', de Area:' + list_device_capture[index]['area'] + '\n';
                }
            }
            if (pc_inactive > 0) {
                if (SETTINGS['send_mail_inactive_pc'] == 'Y') {
                    connection.query('SELECT * FROM users WHERE status = "Y" AND (user_type = 1 OR user_type = 2)', function (err, rows) {
                        for (var us = 0; us < rows.length; us++) {
                            mail.sendMail(function () {
                            }, ENV, rows[us]['email'], "Se ha detectado " + pc_inactive + " dispositivos inactivos en la red\n" + text);
                        }
                    });
                }
            }
        }
    });
}

function start_scan_network() {
    net_discover.start(function (data) {
        connection.query('DELETE FROM nmap_all_scan');
        connection.query('TRUNCATE nmap_all_scan');
        console.log("-----> Finish scan network, restarting");
        for (var i = 0; i < data.length; i++) {
            var query = 'INSERT INTO nmap_all_scan (ip, mac, manufacturer) VALUES ("' + data[i].ip + '","' + data[i].mac + '","' + data[i].manufacturer + '");';
            connection.query(query);
        }
        send_data_scan(data);
    }, SETTINGS);
}

function start_bandwidth() {
    iftop.start(function (data) {
        io.sockets.emit('captured_packets_2', data);
    }, SETTINGS);
}

function start_saturation() {
    setInterval(function () {
        var saturation = iftop.getSaturation();
        if (saturation > SETTINGS['max_bandwidth_saturation'] && SETTINGS['send_mail_saturation'] == 'Y') {
            connection.query('SELECT * FROM users WHERE status = "Y" AND (user_type = 1 OR user_type = 2)', function (err, rows) {
                for (var us = 0; us < rows.length; us++) {
                    mail.sendMail(function () {
                    }, ENV, rows[us]['email'], "Se ha detectado saturacion en uso de la red a:" + (saturation / 1000) + " Mpbs");
                }
            });
            io.sockets.emit('bandwidth_saturation', saturation);
        }
    }, 1000 * SETTINGS['interval_send_saturation']);
}

function start_nmap_ports() {
    nmap_ports.start(function (data) {
        console.log('-----> Scan all ports finished, starting new scan');
        var text = "";
        for (var index = 0; index < data.length; index++) {
            var number_services_unknown = 0;
            for (var j = 0; j < data[index].ports.length; j++) {
                if (data[index].ports[j].service === 'unknown') {
                    number_services_unknown++;
                }
            }
            if (number_services_unknown > 0) {
                connection.query('INSERT INTO alerts (type, ip_src, ip_dst, created_at) VALUES ("Puerta trasera (Backdoor)","' + data[index].ip + '","' + (data[index].ip + '; Puertos desconocidos detectados <b>' + number_services_unknown + '</b>') + '",NOW());');
                text += "IP: " + data[index].ip + ", nuemro depuertos desconocido abiertos: " + number_services_unknown + "\n";
            }
        }

        if (text != "" && SETTINGS['send_mail_backdoor'] == 'Y') {
            connection.query('SELECT * FROM users WHERE status = "Y" AND (user_type = 1 OR user_type = 2)', function (err, rows) {
                for (var us = 0; us < rows.length; us++) {
                    mail.sendMail(function () {

                    }, ENV, rows[us]['email'], "Se ha detectado un Backdoor en: \n" + text);
                }
            });
        }

        io.sockets.emit('scan_all_ports', data);
    }, SETTINGS);
}

function start_statistics() {
    statistics.start(function (data) {
        data.current_max_usage = iftop.getMaxUsage();
        io.sockets.emit('statistics', data);
    }, SETTINGS);
}

function start_denial_service() {
    tcpdump.start(function (data) {
        data.date = new Date();
        io.sockets.emit('alert_denial_service', data);
        connection.query('INSERT INTO alerts (type, ip_src, ip_dst, created_at) VALUES ("Denegacion de servicios (Denial of service)","' + data.src + '","' + data.dst + '",NOW());');
        if (SETTINGS['send_mail_dos'] == 'Y') {
            connection.query('SELECT * FROM users WHERE status = "Y" AND (user_type = 1 OR user_type = 2)', function (err, rows) {
                for (var us = 0; us < rows.length; us++) {
                    mail.sendMail(function () {
                    }, ENV, rows[us]['email'], "Se ha detectado un ataque de Denegacion de servicios , de ip: " + data.src + " a ip: " + data.dst);
                }
            });
        }
    }, SETTINGS);
}

function start_snmp() {
    snmp.start(function (data) {
        var query = 'INSERT INTO snmp_scan (ip,hardware,time_ticks,contact,machine_name,location,updated_at) VALUES(' +
            '"' + data.ip + '",' +
            '"' + data.hardware + '",' +
            '"' + data.time_ticks + '",' +
            '"' + data.contact + '",' +
            '"' + data.machine_name + '",' +
            '"' + data.location + '",' +
            'NOW());';
        settings.connection.query(query);
    }, SETTINGS);
}

io.on('connection', function (socket) {
    number_clients++;
    console.log("Machine connected from: ", socket.handshake.headers.origin);
    connection.query('SELECT * from nmap_all_scan', function (err, rows) {
        if (err) throw err;
        send_data_scan(rows);
    });

    var redisClient = redis.createClient();
    redisClient.subscribe('message');

    redisClient.on("message", function (channel, message) {
        console.log("mew message in queue " + message + "channel");
        socket.emit(channel, message);
    });

    socket.on('disconnect', function () {
        redisClient.quit();
        number_clients--;
    });
});
