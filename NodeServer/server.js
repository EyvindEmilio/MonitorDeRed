var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
var mysql = require('mysql');
var exec = require('child_process').exec;


var connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'root',
    password: 'emilio',
    database: 'monitor_red'
});
connection.connect();
var number_clients = 0;
var SETTINGS = {};
var watch = require('node-watch');
var fs = require("fs");
/*
 setInterval(function () {
 fs.readFile('/root/MonitorDeRed/NodeServer/output.txt', 'utf8', function (err, data_output) {
 if (err) throw err;
 fs.writeFile('/root/MonitorDeRed/NodeServer/output.txt', '', function (err) {
 if (err) throw err;
 console.log('complete');
 });

 data = data_output.split('\n');
 for (var i = 0; i < data.length - 1; i++) {
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
 //json_data['date'] = json_data['date'];

 json_data['src'] = {};
 tmp_ip = line_array[2].split('.');
 json_data['src']['ip'] = tmp_ip[0] + '.' + tmp_ip[1] + '.' + tmp_ip[2] + '.' + tmp_ip[3];
 json_data['src']['port'] = tmp_ip[4] && tmp_ip[4].replace(':', '');

 json_data['dst'] = {};
 tmp_ip = line_array[4].split('.');
 json_data['dst']['ip'] = tmp_ip[0] + '.' + tmp_ip[1] + '.' + tmp_ip[2] + '.' + tmp_ip[3];
 json_data['dst']['port'] = tmp_ip[4] && tmp_ip[4].replace(':', '');

 json_data['size'] = Math.round(parseFloat(line_array[6])) / 1000.0;

 if (json_data['size'] > 0 && number_clients > 0) {
 io.sockets.emit('captured_packets', json_data);
 }
 }


 }
 );

 }, 1000);


 var cmd_monitoring = exec('tcpdump -i eth0 -nnq tcp | tee -a output.txt', {async: true});

 */
connection.query('SELECT * from settings', function (err, rows, fields) {
    if (err) throw err;
    SETTINGS = rows[0];
    server.listen(8890);
    setTimeout(function(){
        start_monitoring();
    },0);
    scan_network();
});
var number_attacks_denial_service = 0;
var ip_attack_denial_service = 0;
var ip_dst_attack_denial_service = 0;

setInterval(function () {
    console.log(number_attacks_denial_service);
    if (number_attacks_denial_service > 7200) {
        io.sockets.emit('alert_denial_service', {
            ip: ip_attack_denial_service,
            number_packets: number_attacks_denial_service,
            date: new Date()
        });
        var query = 'INSERT INTO alerts (type, ip_src, ip_dst, created_at) VALUES ("Denegacion de servicios","' + ip_attack_denial_service + '","' + ip_dst_attack_denial_service + '",NOW());';
        connection.query(query);

    }
    number_attacks_denial_service = 0;

}, 30000);

function start_monitoring() {
    console.log('---- Start monitoring ----');
    var cmd_monitoring = exec('tcpdump -i eth0 -nnq', {async: true});

    function monitoring_on_data(data_output) {
        var data = data_output.split('\n');

        for (var i = 0; i < data.length - 1; i++) {

            var is_contain_denial = false;
            if (data[i].search('ip-proto-17') != -1) {
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
            //json_data['date'] = json_data['date'];

            json_data['src'] = {};
            tmp_ip = line_array[2].split('.');
            json_data['src']['ip'] = tmp_ip[0] + '.' + tmp_ip[1] + '.' + tmp_ip[2] + '.' + tmp_ip[3];
            json_data['src']['port'] = tmp_ip[4] && tmp_ip[4].replace(':', '');

            json_data['dst'] = {};
            tmp_ip = line_array[4].split('.');
            json_data['dst']['ip'] = tmp_ip[0] + '.' + tmp_ip[1] + '.' + tmp_ip[2] + '.' + tmp_ip[3];
            json_data['dst']['ip'] = json_data['dst']['ip'].replace(':', '');
            json_data['dst']['port'] = tmp_ip[4] && tmp_ip[4].replace(':', '');

            json_data['size'] = Math.round(parseFloat(line_array[6])) / 1000.0;

            if (json_data['size'] > 0 && number_clients > 0.1) {
                io.sockets.emit('captured_packets', json_data);
            }
            if (is_contain_denial) {
                ip_attack_denial_service = json_data['src']['ip'];
                ip_dst_attack_denial_service = json_data['dst']['ip'];
            }
        }
    }


    function monitoring_on_exit() {
        cmd_monitoring.stdin.end();
        cmd_monitoring.kill('SIGHUP');
        cmd_monitoring.kill('SIGINT');
        cmd_monitoring.kill();
        console.log('Monitoring has stopped, Restarting..');
        setTimeout(function(){
            start_monitoring();
        },0);
    }

    //noinspection JSUnresolvedVariable
    cmd_monitoring.stdout.on('data', monitoring_on_data);
    cmd_monitoring.on('exit', monitoring_on_exit);


}

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
                send_data_scan(active_pcs);
            }
            setTimeout(scan, 10);
        });
    }

    setTimeout(scan, 10);
}

io.on('connection', function (socket) {
    number_clients++;
    console.log("Maquina conectada");

    connection.query('SELECT * from nmap_all_scan', function (err, rows) {
        if (err) throw err;
        send_data_scan(rows);
    });


    var redisClient = redis.createClient();
    redisClient.subscribe('message');
    socket.emit("hol", "hhh");
    redisClient.on("message", function (channel, message) {
        console.log("mew message in queue " + message + "channel");
        socket.emit(channel, message);
    });

    socket.on('disconnect', function () {
        redisClient.quit();
        number_clients--;
    });

});
