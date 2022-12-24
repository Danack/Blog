FROM debian:11-slim

# USER root

RUN apt-get update -qq \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y nginx \
    ca-certificates curl

RUN chown -R www-data:www-data /var/lib/nginx /var/log/nginx /usr/share/nginx/

WORKDIR /var/www

USER www-data

# CMD echo "what is nginx box doing?"
# CMD tail -f /var/www/README.md

CMD ["/usr/sbin/nginx", "-c", "/var/www/docker/nginx/config/nginx.conf"]
