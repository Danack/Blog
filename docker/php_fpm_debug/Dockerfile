FROM blog_php_fpm:latest

USER root

# TODO xdebug isn't currently stable with php 8.1
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends php8.1-xdebug

COPY xdebug.ini /etc/php/8.1/fpm/conf.d/20-xdebug.ini

USER www-data