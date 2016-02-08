# set -x #echo on
set -eux -o pipefail

dev_environment="centos_guest,dev"
environment="${dev_environment}"

if [ "$#" -ge 1 ]; then
    environment=$1
fi

echo "environment is ${environment}";

if [ "${environment}" != "${dev_environment}" ]; then
    blog_github_access_token=`php bin/info.php "github.access_token"`
    oauthtoken=${blog_github_access_token}
    composer config -g github-oauth.github.com $oauthtoken
    #Run Composer install to get all the dependencies.
    php -d allow_url_fopen=1 /usr/sbin/composer install --no-interaction --prefer-dist --no-dev
fi

#Generate the config files for nginx, etc.
mkdir -p autogen
vendor/bin/configurate -p data/config.php data/config_template/nginx.conf.php autogen/nginx.conf $environment
vendor/bin/configurate -p data/config.php data/config_template/php-fpm.conf.php autogen/php-fpm.conf $environment
vendor/bin/configurate -p data/config.php data/config_template/php.ini.php autogen/php.ini $environment
vendor/bin/configurate -p data/config.php data/config_template/addConfig.sh.php autogen/addConfig.sh $environment

vendor/bin/fpmconv autogen/php.ini autogen/php.fpm.ini
 
vendor/bin/genenv -p data/config.php data/envRequired.php autogen/appEnv.php $environment

#Generate the CSS
mkdir -p ./var/cache/less
php ./bin/compileLess.php

#todo - make everything other than var be not writable 