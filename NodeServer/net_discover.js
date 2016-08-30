/**
 * Created by root on 8/29/16.
 */
var exec = require('child_process').exec;
var fs = require("fs");
var INTERVAL_SCAN_NETWORK = 30;//default in seg

var net_discover = function (onData, settings) {
    var output_text = '';
    // INTERVAL_SCAN_PORTS = parseInt(settings['time_interval_for_scan_ports']);
    function get_info(output) {
        var hosts = output.split('\n');
        var list_hosts = [];
        for (var i = 0; i < hosts.length; i++) {
            var ip = hosts[i].match(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/g);
            if (ip == null) continue;
            ip = ip[0];
            var host = hosts[i].split(/ {1,30}/g);
            var mac = host[2];
            var manufacturer = host.splice(5, host.length).join(' ');
            list_hosts.push({
                ip: ip,
                mac: mac,
                manufacturer: manufacturer
            });
        }
        onData(list_hosts);
    }

    setInterval(function () {
        var args = ' -NPc 10 -r ' + settings['network_address'] + '/' + settings['mask'];
        var child = exec('netdiscover ' + args);
        child.stdout.on('data', function (data) {
            output_text += data.toString('utf8');
        });
        child.on('close', function () {
            get_info(output_text);
            output_text = '';
        });
    }, INTERVAL_SCAN_NETWORK * 1000);

    return this;
};

module.exports.start = net_discover;
// net_discover(null, {network_address: '192.168.1.0', mask: 24});