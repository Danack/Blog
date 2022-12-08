FROM debian:11-slim AS builder

USER root

RUN apt-get update -qq \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y git \
    mariadb-client wget curl \
    ca-certificates lsb-release apt-transport-https gnupg


RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee -a /etc/apt/sources.list.d/php.list \
    && curl https://packages.sury.org/php/apt.gpg | apt-key add - \
    && apt-get update -qq \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y php8.1 php8.1-common php8.1-cli php8.1-fpm \
    php8.1-mysql  php8.1-curl php8.1-gd php8.1-xml php8.1-mbstring \
    php8.1-intl php8.1-redis php8.1-zip tidy graphviz

WORKDIR /var/www

#COPY ./ /var/www

USER www-data

# CMD tail -f /var/www/README.md

CMD /usr/sbin/php-fpm8.1 \
      --nodaemonize \
      --fpm-config=/var/www/docker/php_fpm/config/fpm.conf \
      -c /var/www/docker/php_fpm/config/php.ini


