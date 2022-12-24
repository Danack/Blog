
set -e
set -x

ENV_TO_USE=${ENV_DESCRIPTION:=default}

echo "ENV_TO_USE is ${ENV_TO_USE}";

# php composer.phar update
COMPOSER_TYPE=$(php src/check_composer_command.php)
echo "composer type is ${COMPOSER_TYPE}";
if [ "${COMPOSER_TYPE}" = "update" ]; then
    php composer.phar update
else
    php composer.phar install
fi

## Generate config settings used per environment
# php vendor/bin/configurate \
#    -p config.source.php \
#    autoconf.source.php \
#    autoconf.php \
#    $ENV_TO_USE
#
#
#php vendor/bin/configurate \
#    -p config.source.php \
#    containers/nginx/config/nginx.conf.php \
#    containers/nginx/config/nginx.conf \
#    $ENV_TO_USE
#
#
## Generate config settings used per environment
#php vendor/bin/classconfig \
#    -p config.source.php \
#    "ImagickDemo\\Config" \
#    config.generated.php \
#    $ENV_TO_USE
#
#
## Generate nginx config file for the centos,dev environment
## This is done in installer.
php vendor/bin/configurate \
    -p config.source.php \
    docker/nginx/config/nginx.conf.php \
    docker/nginx/config/nginx.conf \
    $ENV_TO_USE





echo "Installer is finished, site should be available."