#!/bin/bash

function init {
  echo "gpio init";
  gpio -g mode 18 pwm;
}

init;

i="0";
while [ $i -lt 256 ]
do
  echo "gpio to $i";
  gpio -g pwm 18 $i;
  sleep 1;
  i=$[$i+1];

  if [ $i -gt 250 ]; then 
    i="0"
  fi
done

echo "Show's over!";