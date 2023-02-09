#!/bin/bash
redis-server /etc/redis.conf && sleep 3 && redis-sentinel /etc/redis-sentinel.conf
