FROM debian:9-slim

USER root

RUN apt-get update -qq \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y git \
    mariadb-client wget curl \
    ca-certificates lsb-release apt-transport-https gnupg nginx procps

# Install 3rd party PHP 8.1 packages
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee -a /etc/apt/sources.list.d/php.list \
    && curl https://packages.sury.org/php/apt.gpg | apt-key add - \
    && apt-get update -qq \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y php8.1 php8.1-common php8.1-cli php8.1-fpm \
    php8.1-mysql  php8.1-curl php8.1-gd php8.1-xml php8.1-mbstring \
    php8.1-intl php8.1-redis php8.1-zip nginx

#    tidy graphviz

# php8.1-imagick
# php8.1-memcached

WORKDIR /var/www

# Set up configuration volumes
RUN mkdir -p /etc/config

RUN mkdir -p /run/php

# Used by Jenkins to package code in to container
COPY ./ /var/www

RUN rm -rf /etc/nginx/nginx.conf

# CMD ["/usr/sbin/php-fpm8.1", "--nodaemonize", "--fpm-config=/etc/config/fpm.conf", "-c", "/etc/config/php.ini"]

#CMD /usr/sbin/php-fpm8.1 \
#      --nodaemonize \
#      --fpm-config=/var/www/docker/php_backend/config/fpm.conf \
#      -c /var/www/docker/php_backend/config/php.ini

# CMD ["/usr/sbin/nginx", "-c", "/var/www/docker/server/config/nginx/nginx.conf"]

# CMD bash docker/server/run_two_things.sh

CMD tail -f README.md