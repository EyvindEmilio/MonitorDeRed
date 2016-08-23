/**
 * Created by root on 8/20/16.
 */
var exec = require('child_process').exec;
var fs = require("fs");
var INTERVAL_SCAN_PORTS = 30;
var nmap = function (onData, settings) {
    var output_text = '';

    function get_info(output) {
        var list = output.match(/Nmap scan report for [ a-zA-Z.\-0-9]+\(?\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\)?\n(([0-9a-zA-Z(). -:/\t]+)\n)+/g);
        var list_object = [];
        if (list == null)return;
        for (var i = 0; i < list.length; i++) {
            var mac = list[i].match(/[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}:[0-9a-zA-Z]{1,2}/g);
            var latency = list[i].match(/(\d+.\d+s latency)/g)[0];
            latency = parseFloat(latency.replace(/[() a-z] latency/, ''));
            var ip = list[i].match(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/g)[0];
            var list_open_ports = list[i].match(/\d+\/[a-zA-Z]{1,5}[ \t]+[a-zA-Z]{4,6}[ \t]+[a-zA-Z-() 0-9]+/g);
            if (mac == null && i + 1 == list.length) {
                mac = 'server';
            } else {
                mac = mac[0]
            }
            var object = {
                ip: ip,
                mac: mac,
                latency: latency,
                ports: []
            };
            if (list_open_ports != null) {
                for (var j = 0; j < list_open_ports.length; j++) {
                    var line = list_open_ports[j].split(/[ \t]+/g);
                    // console.log(line);
                    object.ports.push({
                        port: line[0],
                        status: line[1],
                        service: line[2]
                    });
                }
            }
            list_object.push(object);
        }
        onData(list_object);
    }

    function init() {
        var args = ' -Pn ' + settings['network_address'] + '/' + settings['mask'];
        var child = exec('nmap ' + args);
        child.stdout.on('data', function (data) {
            output_text += data.toString('utf8');
        });
        child.on('close', function () {
            get_info(output_text);
            output_text = '';
            setTimeout(init, INTERVAL_SCAN_PORTS * 1000);
        });
    }

    init();

    return this;
};
module.exports.start = nmap;