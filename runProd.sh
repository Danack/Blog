#!/usr/bin/env bash

docker-compose build --no-cache
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d blog_php_fpm nginx
