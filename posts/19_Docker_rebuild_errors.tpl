
A couple of times I've seen a weird error when rebuilding docker boxes.

The error looks like there is an error in the repository for where the packages are stored, with at least one of the packages missing.

<!-- end_preview -->

For example:

```
Step 2/3 : RUN DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends php7.2-xdebug
---> Running in 3ba18b5a0627
Reading package lists...
Building dependency tree...
Reading state information...
The following NEW packages will be installed:
php-xdebug
0 upgraded, 1 newly installed, 0 to remove and 1 not upgraded.
Need to get 1074 kB of archives.
After this operation, 6381 kB of additional disk space will be used.
Err:1 https://packages.sury.org/php stretch/main amd64 php-xdebug amd64 2.6.0+2.5.5-1+0~20180205132619.2+stretch~1.gbpc24c95
404  Not Found
E: Failed to fetch https://packages.sury.org/php/pool/main/x/xdebug/php-xdebug_2.6.0+2.5.5-1+0~20180205132619.2+stretch~1.gbpc24c95_amd64.deb  404  Not Found
E: Unable to fetch some archives, maybe run apt-get update or try with --fix-missing?

```

This type of error is possible when you have two separate docker containers where one container inherits from the other. I use this inheritance sparingly, but it is appropriate for defining a container for debugging than differs from the normal php_fpm container only by having Xdebug added.

The Dockerfile for the child blog_php_fpm_debug container that includes Xdebug looks like this:

```
FROM blog_php_fpm:latest

# TODO xdebug isn't currently stable with php 7.2
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends php7.2-xdebug

COPY xdebug.ini /etc/php/7.2/fpm/conf.d/20-xdebug.ini
```


The cause appears to be that Docker has rebuilt some of the containers, but not all of them, which has left them in a bad state. In particular:


* The base container blog_php_fpm was been built a while ago, and has old information about where to download Xdebug from packages.sury.org.

* Docker thinks the base container is up-to-date.

* Docker thinks the child container blog_php_fpm_debug needs to be rebuilt.

Because Docker creates the child blog_php_fpm_debug container based on an out-of-date parent blog_php_fpm it has out-of-date information about where to find the Xdebug extension.

The solution to this is to make Docker rebuild everything from scratch. You could probably accomplish this by blowing away all of the docker containers on a system, but you can do it more elegantly by doing:

```
docker-compose build --no-cache
```


By the way, if you have a CI system that supports running scheduled tasks ala cron, doing a `docker-compose build --no-cache` each day will help keep all of your containers up-to-data, which will both help stop this, and avoid long build times when updates do occur.