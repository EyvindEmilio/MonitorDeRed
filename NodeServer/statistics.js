/**
 * Created by root on 8/20/16.
 */

var spawn = require('child_process').spawn;
var fs = require("fs");
var watch = require('node-watch');

var INTERVAL_REFRESH_STATISTICS = 5;//in seg
var statistics = function (onData, settings) {
    setInterval(function () {
        var query = 'SELECT network_usage.ip, network_usage.size, devices.name, areas.name AS area, areas.id AS area_id, (SELECT device_types.name FROM device_types WHERE device_types.id = devices.device_type) as type FROM network_usage LEFT JOIN devices ON devices.ip = network_usage.ip LEFT JOIN areas ON areas.id = devices.area WHERE network_usage.date = date(now());'
        settings.connection.query(query, function (err, rows) {
            var list_objects = [];
            for (var i = 0; i < rows.length; i++) {
                list_objects.push({
                    ip: rows[i].ip,
                    size: rows[i].size,
                    name: rows[i].name,
                    area: rows[i].area,
                    type: rows[i].type,
                    area_id: rows[i].area_id
                })
            }
            onData(list_objects);
        })
    }, INTERVAL_REFRESH_STATISTICS * 1000);
    return this;
};

module.exports.start = statistics;