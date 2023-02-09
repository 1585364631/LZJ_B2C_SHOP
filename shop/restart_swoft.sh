#!/bin/bash
container_name="swoft"
for container in $(docker ps -a | grep -i $container_name | awk '{print $1}');
do
  echo "重启容器" $container "内" $container_name "服务";
  docker restart $container;
  #docker exec $container php /var/www/swoft/bin/swoft rpc:restart;
done;
