<?php

$config = <<< END

rm -f /etc/nginx/sites-enabled/blog.nginx.conf
rm -f /etc/php-fpm.d/blog.php-fpm.conf
rm -f /etc/php-fpm.d/blog.php.fpm.ini

ln -sfn ${'blog.root.directory'}/autogen/nginx.conf /etc/nginx/sites-enabled/blog.nginx.conf
ln -sfn ${'blog.root.directory'}/autogen/php-fpm.conf /etc/php-fpm.d/blog.php-fpm.conf
ln -sfn ${'blog.root.directory'}/autogen/php.fpm.ini /etc/php-fpm.d/blog.php.fpm.ini

END;

return $config;
