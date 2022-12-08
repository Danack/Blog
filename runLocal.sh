#!/usr/bin/env bash

set -e
set -x

#docker-compose build blog_php_fpm

docker-compose up --build --remove-orphans installer

docker-compose up --build blog_php_fpm blog_php_fpm_debug nginx

# 'echo never > /sys/kernel/mm/transparent_hugepage/enabled' as root, and add it to your /etc/rc.local in order to retain the setting after a