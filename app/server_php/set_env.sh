#!/bin/bash

printf "$(env | grep APP_)\n $(cat /usr/src/app/server_php/crontab)" > /usr/src/app/server_php/crontab

printf "# Environment variables added on Docker run\n\n$(cat /usr/src/app/server_php/crontab)" > /usr/src/app/server_php/crontab


echo "" >> /usr/src/app/server_php/crontab
echo "" >> /usr/src/app/server_php/crontab

chmod 644 /usr/src/app/server_php/crontab
cp /usr/src/app/server_php/crontab /etc/cron.d/server_php
