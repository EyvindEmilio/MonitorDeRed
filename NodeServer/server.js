var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
var mysql = require('mysql');
var exec = require('child_process').exec;
var spawn = require('child_process').spawn;
var fs = require("fs");
var watch = require('node-watch');
var iftop = require('./iftop');
var nmap_ports = require('./nmap_ports');
var statistics = require('./statistics');
var tcpdump = require('./tcpdump');
var net_discover = require('./net_discover');

var connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'root',
    password: 'adriana95',
    database: 'monitor_red'
});

connection.connect();
var number_clients = 0;
var SETTINGS = {};
var INTERVAL_SEND_DATA_MONITORING = 1;//default 1 seg

connection.query('SELECT * from settings', function (err, rows) {
    if (err) throw err;
    SETTINGS = rows[0];
    SETTINGS.connection = connection;//assign connection for other modules
    INTERVAL_SEND_DATA_MONITORING = parseInt(SETTINGS['time_interval_for_sending_monitoring_data']);

    server.listen(8890);

    // scan_network();
    start_scan_network();
    start_bandwidth();
    start_nmap_ports();
    start_statistics();
    start_denial_service();
});

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
        }
    });
}

function start_scan_network() {
    net_discover.start(function (data) {
        connection.query('DELETE FROM nmap_all_scan');
        connection.query('TRUNCATE nmap_all_scan');
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

function start_nmap_ports() {
    nmap_ports.start(function (data) {
        console.log('-----> scan all ports finished, starting new scan');
        for (var index = 0; index < data.length; index++) {
            var number_services_unknown = 0;
            for (var j = 0; j < data[index].ports.length; j++) {
                if (data[index].ports[j].service === 'unknown') {
                    number_services_unknown++;
                }
            }
            if (number_services_unknown > 0) {
                connection.query('INSERT INTO alerts (type, ip_src, ip_dst, created_at) VALUES ("Puerta trasera (Backdoor)","' + data[index].ip + '","' + (data[index].ip + '; Puertos desconocidos detectados <b>' + number_services_unknown + '</b>') + '",NOW());');
            }
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
