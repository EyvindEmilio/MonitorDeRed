/**
 * Created by root on 8/20/16.
 */

var spawn = require('child_process').spawn;
var fs = require("fs");

var DOS_TIME_FOR_CHECK_ATTACKS = 30;//default in seg
var DOS_MAX_PACKETS_RECEIVED = 10000;//default in seg
var probable_list_dos = [];

var tcpdump = function (onData, settings) {
    DOS_TIME_FOR_CHECK_ATTACKS = parseInt(settings['dos_time_for_check_attacks']);
    DOS_MAX_PACKETS_RECEIVED = parseInt(settings['dos_max_packets_received']);

    setInterval(function () {
        for (var i = 0; i < probable_list_dos.length; i++) {
            console.log('Number request from ip-proto from "' + probable_list_dos[i].src + '": ' + probable_list_dos[i].number_attacks_dos);
            if (probable_list_dos[i].number_attacks_dos > DOS_MAX_PACKETS_RECEIVED) {
                onData(probable_list_dos[i]); //Attack !!
            }
        }
        probable_list_dos = [];
    }, (DOS_TIME_FOR_CHECK_ATTACKS * 1000));

    function setDenialSrcDst(ip_src, ip_dst) {
        var exist = false;
        for (var i = 0; i < probable_list_dos.length; i++) {
            if (probable_list_dos[i].src == ip_src && probable_list_dos[i].dst == ip_dst) {
                probable_list_dos[i].number_attacks_dos++;
                exist = true;
                break;
            }
        }
        if (!exist) probable_list_dos.push({src: ip_src, dst: ip_dst, number_attacks_dos: 1});
    }

    function get_info(line_output) {
        if (line_output.search('ip-proto') != -1) {
            var ips = line_output.match(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/g);
            if (ips != null) setDenialSrcDst(ips[0], ips[1]);
        }
    }

    function init() {
        // var child = spawn('./tcpdump.sh');
        var child = spawn('tcpdump', ' -i wlan0 -nnq -t not arp -l'.split(' '));

        child.stdout.on('data', function (data) {
            var lines = data.toString('utf8').split('\n');
            for (var i = 0; i < lines.length; i++) {
                get_info(lines[i]);
            }
        });

        child.on('close', function () {
            setTimeout(init, 0);
            console.log('tcpdump has stooped, restarting');
        });
    }

    setTimeout(init, 0);

    return this;
};

module.exports.start = tcpdump;

// tcpdump(null, {
//     network_address: '192.168.1.0',
//     mask: 24,
//     dos_time_for_check_attacks: 10,
//     DOS_MAX_PACKETS_RECEIVED: 10000
// });
