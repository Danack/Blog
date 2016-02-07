

### Setting up for stub environment

```
git clone https://github.com/danack/blog
cd blog
composer install --no-dev
mkdir -p autogen
vendor/bin/genenv -p data/config.php data/envRequired.php autogen/appEnv.php centos_guest,dev,stub_all_the_things
php -S localhost:8000 -t public

```

To view the light color scheme, chuck a `?light` in the query string:

http://127.0.0.1:8000/?light


### To recompile less to CSS

```
php ./bin/compileLess.php

```