#!/bin/bash
  lock=1
  while [ $lock -le 60 ]
  do
     sleep 3;
     lock=$(( $lock + 1 ))
     html=$(curl -s swoft0:18306);
     if [ "$html" != "" ]
     then
       sleep 3;
       echo "启动nginx";
       /usr/local/nginx/sbin/nginx;
       echo "启动完毕";
       tail -f /dev/null;
       break;
     fi
  done
