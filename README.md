

### Setting up for stub environment

```
git clone https://github.com/danack/blog
cd blog
composer install --no-dev
mkdir -p autogen
vendor/bin/genenv -p data/config.php data/envRequired.php autogen/appEnv.php centos_guest,dev,stub_all_the_things
php -S localhost:8000 -t public

```

### To recompile less to CSS

```
php ./bin/compileLess.php

```


### Setup

npm init
npm install --save react react-dom babel-cli

 Install babel
npm install --save-dev babel-plugin-transform-react-jsx
npm install babili --save-dev


### Build optimised crap

npm run build


## Run the server locally


php -S 127.0.0.1:8000 -t www/



babel --plugins transform-react-jsx www/js/app/main.js

TODO - use webpack following:

http://moduscreate.com/optimizing-react-es6-webpack-production-build/


https://stackoverflow.com/documentation/reactjs/62