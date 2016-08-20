/**
 * Created by root on 8/20/16.
 */

var spawn = require('child_process').spawn;
var exec = require('child_process').exec;
var fs = require("fs");
var tcpdump = function () {
    var SECONDS_DELAYED = 1;
    var child = null;
    var callback_data = null;
    var callback_error = null;
    var TYPE = null;

    var buffer_lines = [];

    this.start = function (args) {
        function init() {
            if (args.search('tcp') != -1) {
                TYPE = 'tcp';
            } else if (args.search('icmp') != -1) {
                TYPE = 'icmp';
                fs.truncate('out_icmp.cap', 0, function () {
                });
                child = spawn('./tcpdump_out.sh', []);
            }
            child.on('close', function (code) {
                console.log('Close ' + TYPE + ', restaring..');
                init();
            });
        }

        init();
        console.log('- Start: ' + TYPE);

    };
    function getDataProcess(data) {
        var text_data = data.toString('utf8');
        var lines = text_data.split('\n');

        var object_line, tmp_line, tmp_buffer = [], ips, size;
        for (var index = 0; index < lines.length; index++) {
            var current_line = lines[index];
            tmp_line = lines[index].split(' ');
            if (tmp_line[0] !== 'IP') {
                continue;
            }

            if (TYPE === 'tcp') {
                size = current_line.match(/tcp \d{1,10}/)[0].replace('tcp ', '');
                if (parseFloat(size) == 0) {
                    continue;
                }
                ips = current_line.match(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/g);
                var port_src = current_line.match(/\d{1,10} >/)[0].replace(' >', '');
                var port_dst = current_line.match(/\d{1,10}:/)[0].replace(':', '');
                object_line = {
                    src: {ip: ips[0], port: port_src},
                    dst: {ip: ips[1], port: port_dst},
                    size: parseFloat(size) / 1000.0
                };

                var exist = false;
                for (var i = tmp_buffer.length - 2; i >= 0; i--) {
                    if (tmp_buffer[i].src.ip == object_line.src.ip && tmp_buffer[i].dst.ip == object_line.dst.ip) {
                        tmp_buffer[i].size += object_line.size;
                        exist = true;
                        break;
                    }
                }
                if (!exist) {
                    tmp_buffer.push(object_line);
                }
            } else if (TYPE === 'icmp') {
                size = current_line.match(/length \d{1,10}/);
                size = (size && size[0].replace('length ', '')) || 1;
                ips = current_line.match(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/g);
                var port = current_line.match(/port \d{1,10}/);
                port = port && port[0].replace('port ', '');
                object_line = {
                    src: {ip: ips[1], port: port},
                    dst: {ip: ips[0], port: port},
                    size: parseFloat(size) / 1000.0
                };
                tmp_buffer.push(object_line);
            }
        }
        buffer_lines = buffer_lines.concat(tmp_buffer);
    }

    var COUNT = 0;
    setInterval(function () {
        if (child != null) {
            if (TYPE === 'icmp') {
                fs.readFile('out_icmp.cap', 'utf8', function (err, data_output) {
                        if (err) throw err;
                        getDataProcess(data_output);
                        fs.truncate('out_icmp.cap', 0, function () {
                            // console.log('done')
                        });
                    }
                );
            }
            COUNT += buffer_lines.length;
            console.log(COUNT);
            buffer_lines = [];
        }
    }, SECONDS_DELAYED * 1000);


    this.on = function (type_status, callback) {
        if (type_status === 'data') {
            callback_data = callback;
        } else if (type_status === 'exit') {
            callback_error = callback;
        }
    };

    this.finish = function () {
        if (child != null) {
            child.kill('SIGHUP');
            child.kill();
            child = null;
        }
    };


//tcpdump -i eth0 -nnq -t
};

var ff = new tcpdump();
ff.start('-i eth0 -nnq -t icmp');