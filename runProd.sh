#!/usr/bin/env bash

docker-compose build --no-cache blog_php_fpm nginx
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up --build --remove-orphans installer
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d blog_php_fpm nginx

# docker-compose -f docker-compose.yml -f docker-compose.prod.yml up blog_php_fpm nginx