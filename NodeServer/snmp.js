/**
 * Created by root on 8/20/16.
 */

var spawn = require('child_process').spawn;
var fs = require("fs");
var watch = require('node-watch');

var INTERVAL_SNMP_SCAN = 30;
var snmp = function (onData, settings) {

    // INTERVAL_SNMP_SCAN = settings['interval_snmp_scan'];
    function load() {
        settings.connection.query('SELECT * FROM devices', function (err, rows) {
            if (rows.length > 0)start(rows);
        });
    }

    function start(list_devices) {
        function snmp_read(index) {
            if (index >= list_devices.length) {
                setTimeout(load, INTERVAL_SNMP_SCAN * 1000);
                return;
            }
            var device = list_devices[index];
            var args = ('-v 2c -c public ' + device['ip'] + ' iso.3.6.1.2.1.1').split(' ');
            var cmd = spawn('snmpwalk', args);
            cmd.stdout.on('data', function (data) {
                /*
                 * iso.3.6.1.2.1.1.1.0 = STRING: "Hardware: Intel64 Family 6 Model 58 Stepping 9 AT/AT COMPATIBLE - Software: Windows Version 6.3 (Build 10586 Multiprocessor Free)"
                 iso.3.6.1.2.1.1.2.0 = OID: iso.3.6.1.4.1.311.1.1.3.1.1
                 iso.3.6.1.2.1.1.3.0 = Timeticks: (5300403) 14:43:24.03
                 iso.3.6.1.2.1.1.4.0 = STRING: "eyvind.coaquira@gmail.com"
                 iso.3.6.1.2.1.1.5.0 = STRING: "TININI-PC"
                 iso.3.6.1.2.1.1.6.0 = STRING: "Oficina Eyv"
                 iso.3.6.1.2.1.1.7.0 = INTEGER: 4
                 * */
                data = data.toString('utf8');
                try {
                    var hardware = data.match(/^(iso.3.6.1.2.1.1.1.0 = STRING: )[" a-zA-Z0-9:\-_;()/\\.]+/g)[0].replace(/"/g, '').replace('iso.3.6.1.2.1.1.1.0 = STRING: ', '');
                    var time_ticks = data.match(/iso.3.6.1.2.1.1.3.0 = Timeticks: [ ()0-9:.]+/g)[0].replace(/\(\d+\) /g, '').replace('iso.3.6.1.2.1.1.3.0 = Timeticks: ', '');
                    var contact = data.match(/iso.3.6.1.2.1.1.4.0 = STRING: ["a-zA-Z@\- ()0-9:;\\.]+/g)[0].replace(/"/g, '').replace('iso.3.6.1.2.1.1.4.0 = STRING: ', '');
                    var machine_name = data.match(/iso.3.6.1.2.1.1.5.0 = STRING: ["a-zA-Z@\- ()0-9:;\\.]+/g)[0].replace(/"/g, '').replace('iso.3.6.1.2.1.1.5.0 = STRING: ', '');
                    var location = data.match(/iso.3.6.1.2.1.1.6.0 = STRING: ["a-zA-Z@\- ()0-9:;\\.]+/g)[0].replace(/"/g, '').replace('iso.3.6.1.2.1.1.6.0 = STRING: ', '');
                    onData({
                        ip: list_devices[index]['ip'],
                        hardware: hardware,
                        time_ticks: time_ticks,
                        contact: contact,
                        machine_name: machine_name,
                        location: location
                    });
                } catch (err) {

                }
                snmp_read(index + 1);
            });
            cmd.on('close', function () {
                snmp_read(index + 1);
            });
        }

        snmp_read(0);
    }

    load();
    return this;
};

module.exports.start = snmp;
/*
 iftop(function (data) {
 console.log(data);
 console.log('******************************');
 }, {network_address: '192.168.1.0', mask: 24});*/
