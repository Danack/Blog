# set -x #echo on
set -eux -o pipefail

environment="centos_guest,dev"

if [ "$#" -ge 1 ]; then
    environment=$1
fi

echo "environment is ${environment}";

if [ "${environment}" != "centos_guest" ]; then
    blog_github_access_token=`php bin/info.php "github.access_token"`
    oauthtoken=${blog_github_access_token}
    composer config -g github-oauth.github.com $oauthtoken
    #Run Composer install to get all the dependencies.
    php -d allow_url_fopen=1 /usr/sbin/composer install --no-interaction --prefer-dist
fi

#need to make dir?
mkdir -p ./var/cache/less
mkdir -p autogen

#Generate the config files for nginx, etc.
vendor/bin/configurate -p data/config.php data/config/nginx.conf.php autogen/blog.nginx.conf $environment
vendor/bin/configurate -p data/config.php data/config/php-fpm.conf.php autogen/blog.php-fpm.conf $environment
vendor/bin/configurate -p data/config.php data/config/project.php.ini.php autogen/blog.php.ini $environment
vendor/bin/configurate -p data/config.php data/config/addConfig.sh.php autogen/addblog.sh $environment
vendor/bin/fpmconv autogen/blog.php.ini autogen/blog.php.fpm.ini 
vendor/bin/genenv -p data/config.php data/envRequired.php autogen/appEnv.php $environment

#Generate some code.
#php ./tool/weaveControls.php
#Generate the CSS
php ./bin/compileLess.php

# php bin/cli.php clearRedis


#todo - make everything other than var be not writable 