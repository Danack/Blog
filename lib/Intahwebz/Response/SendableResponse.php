<?php


namespace Intahwebz\Response;


class SendableResponse {

    
    private $otherHeaders = [];
    
    static private $standardReasons = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        426 => 'Upgrade Required',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    ];


    /**
     * @param $status
     * @param null $reason
     */
    function createStatusHeader(\Intahwebz\Request $request, $status, $reason = null) {
        $protocol = $request->getProtocol();
        if ($reason == null) {
            if (!isset(self::$standardReasons[$status])) {
                throw new \Exception("Status $status does not have a standard readon defined, you must provide one.");
            }

            $reason = self::$standardReasons[$status];
        }

        return sprintf(
            "HTTP/%s %s %s",
            $request->getProtocol(),
            $status,
            $reason
        );
    }

    /**
     * 
     */
    protected function sendHeaders($headers) {
        foreach ($headers as $type => $value) {
            if (is_int($type)) {
                header($value);
            }
            else {
                header("$type: $value");
            }
        }

        foreach ($this->otherHeaders as $type => $value) {
            if (is_int($type)) {
                header($value);
            }
            else {
                header("$type: $value");
            }
        }
    }


    function unsetCookie($cookieName) {
        $domainToSet = 'basereality.test';
        $domainForCookie = '.'.$domainToSet; //leading dot according to http://www.faqs.org/rfcs/rfc2109.html
        
        //($name, $value, $expires = null, $path = null, $domain = '', $secure = false, $httpOnly = true)
        $cookie = new \Amp\Artax\Cookie\Cookie(
            $cookieName,
            false,
            time() - (25 * 3600), '/',
            $domainForCookie,
            true,
            true
        );
                
        $this->otherHeaders[] = $cookie->__toString();

        if(isset($_COOKIE[$cookieName])){
            unset($_COOKIE[$cookieName]);
        }
    }
}

 