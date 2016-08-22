/**
 * Created by root on 8/20/16.
 */

var spawn = require('child_process').spawn;
var fs = require("fs");
var watch = require('node-watch');
/*
 fs.readFile('iftop.out', 'utf8', function (err, data) {
 var matches = data.match(/([\d]+ [0-9.]+[ \t]+[<>=]{1,2}[ \t]+([0-9.a-zA-Z]+[ \t]+)+[0-9.a-zA-Z]+)\n?([\t ]+ [0-9.]+[ \t]+[<>=]{1,2}[ \t]+([0-9.a-zA-Z]+[ \t]+)+[0-9.a-zA-Z]+)/g);
 for (var i = 0; i < matches.length; i++) {
 console.log(matches[i]);
 console.log("*********************************************************************************************************************************");
 }

 /!*var matches = data.match(/# Host name[a-zA-Z0-9 \-().:=<>\[\]/\t\n\r]+[=]{50,1000}/g);
 matches = matches[0].match(/[\d ]+ [0-9.]+[ \t]+[<>=]{1,2}[ \t]+([0-9.a-zA-Z]+[ \t]+)+/g);

 for (var i = 0; i < matches.length; i++) {
 console.log(matches[i]);
 console.log("*********************************************************************************************************************************");
 }*!/
 });
 */

var iftop = function (onData, settings) {
    var args = [];
    args[0] = settings['network_address'];
    args[1] = settings['mask'];
    cmd = spawn('./iftop.sh', args);
    cmd.stdout.on('data', function (data) {
    });
    cmd.on('close', function () {
        console.log('Close iftop, restaring..');
    });
    fs.truncate('iftop.out', 0, function () {
        chage_by_truncate = true;
    });


    var chage_by_truncate = false;

    watch('iftop.out', function () {
        if (chage_by_truncate) {
            chage_by_truncate = false;
            return;
        }
        fs.readFile('iftop.out', 'utf8', function (err, data) {
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
            }

            fs.truncate('iftop.out', 0, function () {
                chage_by_truncate = true;
            });

            onData(list_objects);
        });
    });
    return this;
};

module.exports.start = iftop;