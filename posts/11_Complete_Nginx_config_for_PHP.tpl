Nginx is awesome. PHP-FPM is awesome. Nginx with PHP-FPM is really awesome. However the documentation for how to link the two of them up through the config files is not particularly awesome.

<!-- end_preview -->

Below is the set of config files used to configure nginx and a website. The only things that are noticeable the config are:

* It's for a tiny website that runs on an Amazon micro instance. For a real website, you would need many more workers.

* the part in the Nginx config for the site that does the `try_files`.

```
    set $originalURI  $uri;
    try_files $uri /routing.php /50x_static.html;
    fastcgi_param  QUERY_STRING  q=$originalURI&$query_string;
```

Almost every other example of how to configure PHP-FPM with Nginx has the Front Controller php file as the last parameter for the try_files line. That is fine, except that hitting the last parameter of try_files causes Nginx to rewrite the request to that last parameter and reprocess the request through all of the Nginx config.

Although that works, it seems a bit silly.

By just saving the original URI and passing that into the QUERY_STRING for PHP-FPM, the additional processing loop is skipped.

### Base Nginx config file that is used by all sites.

{{syntaxHighlighterFile('example_nginx.conf', 'js')}}


### Base PHP-FPM config file that is used by all sites.

{syntaxHighlighterFile lang='js' file='example_php-fpm.conf'}
{/syntaxHighlighterFile}

### Site Nginx config that routes requests to either static files, or the front controller.

{syntaxHighlighterFile lang='js' file='example_site.nginx.conf'}
{/syntaxHighlighterFile}

### PHP-FPM config for a site, which creates pools and workers.

{syntaxHighlighterFile lang='js' file='example_site.php-fpm.conf'}
{/syntaxHighlighterFile}



### FastCGI config, to avoid repetition in the above fle.

{syntaxHighlighter lang='js'}

    fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;

    #QUERY_STRING is not set in here - set it in Nginx to prevent extra redirect
    #fastcgi_param  QUERY_STRING       $query_string;

    fastcgi_param  REQUEST_METHOD     $request_method;
    fastcgi_param  CONTENT_TYPE       $content_type;
    fastcgi_param  CONTENT_LENGTH     $content_length;

    fastcgi_param  SCRIPT_NAME        $fastcgi_script_name;
    fastcgi_param  REQUEST_URI        $request_uri;
    fastcgi_param  DOCUMENT_URI       $document_uri;
    fastcgi_param  DOCUMENT_ROOT      $document_root;
    fastcgi_param  SERVER_PROTOCOL    $server_protocol;
    fastcgi_param  HTTPS              $https if_not_empty;

    fastcgi_param  GATEWAY_INTERFACE  CGI/1.1;
    fastcgi_param  SERVER_SOFTWARE    nginx/$nginx_version;

    fastcgi_param  REMOTE_ADDR        $remote_addr;
    fastcgi_param  REMOTE_PORT        $remote_port;
    fastcgi_param  SERVER_ADDR        $server_addr;
    fastcgi_param  SERVER_PORT        $server_port;
    fastcgi_param  SERVER_NAME        $server_name;

    # PHP only, required if PHP was built with --enable-force-cgi-redirect
    fastcgi_param  REDIRECT_STATUS    200;

{/syntaxHighlighter}

