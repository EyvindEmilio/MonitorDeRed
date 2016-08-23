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
var nmap = require('./nmap');

var connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'root',
    password: 'emilio',
    database: 'monitor_red'
});

connection.connect();
var number_clients = 0;
var SETTINGS = {};

connection.query('SELECT * from settings', function (err, rows) {
    if (err) throw err;
    SETTINGS = rows[0];
    server.listen(8890);
    start_monitoring();
    scan_network();
    start_bandwidth();
    start_nmap_ports();
});

var number_attacks_denial_service = 0;
var ip_attack_denial_service = 0;
var ip_dst_attack_denial_service = 0;
var buffer_lines = [];
var INTERVAL_SEND_DATA_MONITORING = 1;

setInterval(function () {
    console.log('Number request from ip-proto: ' + number_attacks_denial_service);
    if (number_attacks_denial_service > 7200) {
        io.sockets.emit('alert_denial_service', {
            ip: ip_attack_denial_service,
            number_packets: number_attacks_denial_service,
            date: new Date()
        });
        connection.query('INSERT INTO alerts (type, ip_src, ip_dst, created_at) VALUES ("Denegacion de servicios (Denial of service)","' + ip_attack_denial_service + '","' + ip_dst_attack_denial_service + '",NOW());');
    }
    number_attacks_denial_service = 0;
}, 30000);

function start_monitoring() {
    console.log('---- Start monitoring ----');
    var cmd = spawn('./tcpdump_out_server.sh', []);
    cmd.stdout.on('data', function (data) {//not delete,or error not constant send tio file
        // console.log(data.toString('utf8'));
    });

    cmd.on('close', function () {
        console.log('Close monitoring, restarting..');
        start_monitoring();
    });
}

function monitoring_on_data(data_output) {
    var data = data_output.split('\n'), tmp_buffer = [];
    for (var i = 0; i < data.length - 1; i++) {
        var is_contain_denial = false;

        if (data[i].search('ip-proto') != -1) {
            is_contain_denial = true;
            number_attacks_denial_service++;
        }
        var line_array = data[i].split(' ');
        if (line_array.length < 6) {
            continue;
        }
        var json_data = {};
        var tmp_ip = '';
        var tmp_date = (line_array[0].split('.'))[0];
        json_data['date'] = new Date();
        json_data['date'].setHours(tmp_date.split(':')[0]);
        json_data['date'].setMinutes(tmp_date.split(':')[1]);
        json_data['date'].setSeconds(tmp_date.split(':')[2]);
        json_data['date'].setMilliseconds(0);

        json_data['src'] = {};
        tmp_ip = line_array[2].split('.');
        json_data['src']['ip'] = tmp_ip[0] + '.' + tmp_ip[1] + '.' + tmp_ip[2] + '.' + tmp_ip[3];
        json_data['src']['port'] = tmp_ip[4] && tmp_ip[4].replace(':', '');

        json_data['dst'] = {};
        tmp_ip = line_array[4].split('.');
        json_data['dst']['ip'] = tmp_ip[0] + '.' + tmp_ip[1] + '.' + tmp_ip[2] + '.' + tmp_ip[3];
        json_data['dst']['ip'] = json_data['dst']['ip'].replace(':', '');
        json_data['dst']['port'] = tmp_ip[4] && tmp_ip[4].replace(':', '');

        json_data['size'] = parseFloat(line_array[6]);

        var exist = false;
        for (var j = tmp_buffer.length - 1; j >= 0; j--) {
            if (tmp_buffer[j].src.ip == json_data.src.ip && tmp_buffer[j].dst.ip == json_data.dst.ip) {
                tmp_buffer[j].size += json_data.size;
                exist = true;
                break;
            }
        }
        if (!exist) {
            tmp_buffer.push(json_data);
        }
        if (is_contain_denial) {
            ip_attack_denial_service = json_data['src']['ip'];
            ip_dst_attack_denial_service = json_data['dst']['ip'];
        }
    }
    buffer_lines = buffer_lines.concat(tmp_buffer);
}

setInterval(function () {
    fs.readFile('out.cap', 'utf8', function (err, data_output) {
            if (err) throw err;
            monitoring_on_data(data_output);
            for (var i = 0; i < buffer_lines.length; i++) {
                //io.sockets.emit('captured_packets', buffer_lines[i]);
            }
            buffer_lines = [];
            setTimeout(function () {
                fs.truncate('out.cap', 0, function () {
                });
            }, 0);
        }
    );
}, INTERVAL_SEND_DATA_MONITORING * 1000);

function send_data_scan(list_device_capture) {
    connection.query('SELECT * from devices', function (err, rows) {
        if (err) throw err;
        var index, j;
        for (j = 0; j < list_device_capture.length; j++) {
            list_device_capture[j].name = '-- desconocido --';
            list_device_capture[j].status_network = 'N';
            var index_device_in_capture = -1;
            for (index = 0; index < rows.length; index++) {
                if (list_device_capture[j].ip === rows[index].ip) {
                    index_device_in_capture = index;
                    list_device_capture[j].name = rows[index].name;
                    list_device_capture[j].status_network = 'Y';
                }
            }
            if (index_device_in_capture == -1) {
                list_device_capture[j].status_network = 'Y';
            }
        }

        for (index = 0; index < rows.length; index++) {
            var exist = false;
            for (j = 0; j < list_device_capture.length; j++) {
                if (list_device_capture[j].ip === rows[index].ip) {
                    exist = true;
                    break;
                }
            }
            if (!exist) {
                var device_not_active = rows[index];
                device_not_active.status_network = 'N';
                device_not_active.mac = '--';
                device_not_active.manufacturer = '--';
                list_device_capture.push(device_not_active);
            }
        }

        if (number_clients > 0) {
            io.sockets.emit('active_pcs', {date: new Date(), data: list_device_capture});
        }
    });
}

function scan_network() {
    console.log('---- Start scan network----');
    var network_address = SETTINGS['network_address'];
    var network = '192.168.1.0';
    var mask = parseInt(SETTINGS['mask']);
    var tmp_net = network_address.split('.');
    switch (mask) {
        case 32:
            network = network_address;
            break;
        case 24:
            network = tmp_net[0] + '.' + tmp_net[1] + '.' + tmp_net[2] + '.*';
            break;
        case 16:
            network = tmp_net[0] + '.' + tmp_net[1] + '.*.*';
            break;
        case 8:
            network = tmp_net[0] + '.*.*.*';
            break;
        default:
            network = network_address;
            break;
    }

    function scan() {
        exec('nmap -sP ' + network, function (err, stdout) {
            if (err == null) {
                connection.query('DELETE FROM nmap_all_scan');
                connection.query('TRUNCATE nmap_all_scan');
                console.info("------> scan network finished, staring new scan");
                var active_pcs = [];
                var data_output = stdout.split('\n');
                for (var index = 2; index < data_output.length - 4; index += 3) {
                    try {
                        var ip = data_output[index].match(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/)[0];
                        var latency = data_output[index + 1].match(/\d{1,7}\.\d{1,7}/)[0];
                        var manufacturer = data_output[index + 2].match(/\([0-9a-zA-Z -]{1,100}\)/)[0];
                        var mac = data_output[index + 2].match(/[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}/)[0];
                        manufacturer = manufacturer.replace("(", "");
                        manufacturer = manufacturer.replace(")", "");
                        active_pcs.push({ip: ip, latency: latency, manufacturer: manufacturer, mac: mac});
                        var query = 'INSERT INTO nmap_all_scan (ip, mac, latency, manufacturer) VALUES ("' + ip + '","' + mac + '","' + latency + '","' + manufacturer + '");';
                        connection.query(query);
                    } catch (e) {
                    }
                }

                send_data_scan(active_pcs)

            }
            setTimeout(scan, 10000);
        });
    }

    setTimeout(scan, 10);
}

function start_bandwidth() {
    iftop.start(function (data) {
        io.sockets.emit('captured_packets_2', data);
    }, SETTINGS);
}
function start_nmap_ports() {
    nmap.start(function (data) {
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
