var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
var mysql = require('mysql');
var exec = require('child_process').exec;
var spawn = require('child_process').exec;


var connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'root',
    password: 'emilio',
    database: 'monitor_red'
});
connection.connect();

var SETTINGS = {};

connection.query('SELECT * from settings', function (err, rows, fields) {
    if (err) throw err;
    SETTINGS = rows[0];
    server.listen(8890);
    start_monitoring();
});
function start_monitoring() {
    console.log('---- Settings ----');
    console.log(SETTINGS);

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

    // ---- Monitoring
    var cmd_monitoring = exec('tcpdump -i eth0 -nnq tcp', {async: true});

    //noinspection JSUnresolvedVariable
    cmd_monitoring.stdout.on('data', function (err, stdout, stderr) {
        var data = err.split('\n');
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

            json_data['size'] = Math.round((parseFloat(line_array[6]) / 1024.0) * 1000) / 1000.0;
            if (json_data['size'] > 0) {
                io.sockets.emit('captured_packets', json_data);
            }
        }
    });
    cmd_monitoring.on('exit', function (code) {
        console.log('Monitoring has stopped, Restarting..');
        cmd_monitoring = exec('tcpdump -i eth0 -nnq tcp', {async: true});
    });

}

io.on('connection', function (socket) {
    console.log("Maquina conectada");

    var redisClient = redis.createClient();
    redisClient.subscribe('message');
    socket.emit("hol", "hhh");
    redisClient.on("message", function (channel, message) {
        console.log("mew message in queue " + message + "channel");
        socket.emit(channel, message);
    });

    socket.on('disconnect', function () {
        redisClient.quit();
    });

});
