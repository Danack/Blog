
# Run in the root of Docker

# https://www.vultr.com/docs/load-balance-with-docker

# DEFAULT_PORT=9010
# APP_PORT=9000
# DEPLOY_COUNT=3
# NAME="testapp"

docker build -t blog_webserver:latest --file ./docker/web_server/Dockerfile .
docker build -t blog_backend:latest --file ./docker/php_backend/Dockerfile .

docker kill blog_backend_1
docker rm blog_backend_1
docker run \
 --name=blog_backend_1 \
 --network="cheap_hosting" \
 -d \
 blog_backend:latest


 # -p 9000:9000 \
docker kill blog_webserver_1
docker rm blog_webserver_1
docker run \
 --name=blog_webserver_1 \
 --network="cheap_hosting" \
 -d \
 blog_webserver:latest




docker run \
 --name=blog_webserver_1 \
 --network="cheap_hosting" \
 blog_webserver:latest


# docker network connect cheap_hosting app_php_backend
# docker network connect cheap_hosting app_php_backend

#docker kill testapp
#docker rm testapp

# docker run --name testapp -p 9000:9000 app_web_server:latest -d


#for ((i=0; i<DEPLOY; i++)); do
#        docker kill $NAME$i ; docker rm $NAME$i
#        docker run --name $NAME$i -p 127.0.0.1:$(((i * 1000) + DEFAULT_PORT)):$APP_PORT -d danack/testapp
#done

# /app/public

#  --mount 'type=volume,src=/var/home/testapp/public,dst=/var/www/public,volume-driver=local' \
#source=/var/home/testapp/public
#  --mount source=public-vol,target=/var/www/public \

# --restart=always
# --memory="4M"
# --memory-swap=""
# -c, --cpu-shares=0


# docker run -d \
#  -it \
#  --mount type=bind,source=/var/home/testapp/config.php,target=/var/www/config.php \
#  -v /var/home/testapp/public:/var/www/public \
#  --name testapp \
#  -p 9000:9000 \
#  app_php_backend

# destination
# readonly

#/var/home/testapp/public