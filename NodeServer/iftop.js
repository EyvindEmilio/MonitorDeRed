/**
 * Created by root on 8/20/16.
 */

var spawn = require('child_process').spawn;
var fs = require("fs");
var watch = require('node-watch');
var max_usage = 0;

var iftop = function (onData, settings) {
    var args = ('-nN -t -o 2s -F ' + settings['network_address'] + '/' + settings['mask']).split(' ');

    cmd = spawn('iftop', args);
    cmd.stdout.on('data', function (data) {
        data = data.toString('utf8');
        var matches = data.match(/([\d]+ \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}[ \t]+[<>=]{1,2}[ \t]+([0-9.a-zA-Z]+[ \t]+){3}[0-9.a-zA-Z]+)\n?([\t ]+ \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}[ \t]+[<>=]{1,2}[ \t]+([0-9.a-zA-Z]+[ \t]+){3}[0-9.a-zA-Z]+)/g);
        if (matches == null)
            return;
        var list_objects = [], object;
        for (var i = 0; i < matches.length; i++) {
            // console.log(matches);
            // console.log('----------------------------------------------------------------------------------------------------');
            var lines = matches[i].split('\n');
            var ip = lines[0].match(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/)[0];
            var ip_d = lines[1].match(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/)[0];
            var size_len = lines[1].match(/[0-9.]+[a-zA-Z]+/)[0];
            var len = size_len.match(/[a-zA-Z]+/)[0];
            var size = parseFloat(size_len.match(/[0-9.]+/)[0]);
            if (len === 'b') {
                size = size / 1024;
            } else if (len === 'Mb') {
                size = size * 1024;
            }
            size = Math.round(size * 1000) / 1000.0;
            object = {
                src: {ip: ip},
                dst: {ip: ip_d},
                size: size,
                len: len
            };
            var exist = false;
            if ((parseInt(ip.split('.')[0]) > 200)) {
                // console.log(parseInt(ip.split('.')[0]));
            }
            for (var j = 0; j < list_objects.length; j++) {
                if (list_objects[j].src.ip == object.src.ip) {
                    exist = true;
                    if (list_objects[j].size < object.size) {
                        list_objects[j].size = object.size;
                    }
                }
            }
            if (!exist) {
                list_objects.push(object);
            }
            if (object.size > max_usage) max_usage = object.size;
        }

        fs.truncate('iftop.out', 0, function () {
            change_by_truncate = true;
        });

        function save_network_usage(i) {
            if (i >= list_objects.length)return;
            settings.connection.query('SELECT * from network_usage WHERE date = date(now()) AND ip = "' + list_objects[i].src.ip + '";', function (err, rows) {
                if (rows.length > 0) {//add
                    settings.connection.query('UPDATE network_usage SET size=' + (list_objects[i].size + parseFloat(rows[0].size)) + ' WHERE ip="' + list_objects[i].src.ip + '" AND date = date(now());', function () {
                        save_network_usage(i++);
                    });
                } else {//set
                    settings.connection.query('INSERT INTO network_usage (ip, size, date) VALUES("' + list_objects[i].src.ip + '",' + list_objects[i].size + ',date(now()))', function (err2, rows2) {
                        save_network_usage(i++)
                    });
                }
            });
        }

        save_network_usage(0);
        onData(list_objects);
    });
    cmd.on('close', function () {

        console.log('Close iftop, restaring..');
    });
    fs.truncate('iftop.out', 0, function () {
        chage_by_truncate = true;
    });

    setInterval(function () {
        max_usage = 0;
    }, 7000);
    var change_by_truncate = false;

    /*
     watch('iftop.out', function () {
     if (change_by_truncate) {
     change_by_truncate = false;
     return;
     }
     fs.readFile('iftop.out', 'utf8', function (err, data) {

     });
     });
     */

    return this;
};

module.exports.start = iftop;
module.exports.getMaxUsage = function () {
    return max_usage;
};
/*
 iftop(function (data) {
 console.log(data);
 console.log('******************************');
 }, {network_address: '192.168.1.0', mask: 24});*/
