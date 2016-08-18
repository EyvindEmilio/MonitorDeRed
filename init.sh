#!/usr/bin/env bash
service apache2 start
service mysql start
service cron start
service redis-server start
node /root/MonitorDeRed/NodeServer/server.js
