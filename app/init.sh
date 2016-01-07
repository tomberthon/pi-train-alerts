#!/bin/bash

echo "INIT: Start cron";
cron;

echo "INIT: set cron env";
/usr/src/app/server_php/set_env.sh;

echo "INIT: Start Redis Server";
/usr/bin/redis-server &

echo "INIT: Start Node Server" &&
nodejs /usr/src/app/node/server.js &


echo "INIT: Start Go Server" &&
/usr/src/app/./start_go.sh &


echo "INIT: Start X Windows" &&
/usr/src/app/./startx.sh & 

echo "INIT: tail cron log" &&
tail -f /var/log/cron.log

