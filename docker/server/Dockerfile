FROM debian:9-slim

USER root

RUN apt-get update -qq \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y git \
    mariadb-client wget curl \
    ca-certificates lsb-release apt-transport-https gnupg nginx procps

# Install 3rd party PHP 7.2 packages
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee -a /etc/apt/sources.list.d/php.list \
    && curl https://packages.sury.org/php/apt.gpg | apt-key add - \
    && apt-get update -qq \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y php7.2 php7.2-common php7.2-cli php7.2-fpm \
    php7.2-mysql  php7.2-curl php7.2-gd php7.2-xml php7.2-mbstring \
    php7.2-intl php7.2-redis php7.2-zip nginx

#    tidy graphviz

# php7.2-imagick
# php7.2-memcached

WORKDIR /var/www

# Set up configuration volumes
RUN mkdir -p /etc/config

RUN mkdir -p /run/php

# Used by Jenkins to package code in to container
COPY ./ /var/www

RUN rm -rf /etc/nginx/nginx.conf

# CMD ["/usr/sbin/php-fpm7.2", "--nodaemonize", "--fpm-config=/etc/config/fpm.conf", "-c", "/etc/config/php.ini"]

#CMD /usr/sbin/php-fpm7.2 \
#      --nodaemonize \
#      --fpm-config=/var/www/docker/php_backend/config/fpm.conf \
#      -c /var/www/docker/php_backend/config/php.ini

# CMD ["/usr/sbin/nginx", "-c", "/var/www/docker/server/config/nginx/nginx.conf"]

# CMD bash docker/server/run_two_things.sh

CMD tail -f README.md