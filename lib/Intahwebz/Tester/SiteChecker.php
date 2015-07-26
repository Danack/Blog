<?php


namespace Intahwebz\Tester;



class SiteChecker {

    /**
     * @var URLResult[]
     */
    private $urlsChecked = [];

    /** @var URLToCheck[] */
    private $urlsToCheck = [];

    private $siteURL;

    private $count = 0;

    private $session;

    private $driver;


    function __construct($siteURL) {
        $this->siteURL = $siteURL;
        $this->driver = new \Behat\Mink\Driver\GoutteDriver();
        $this->session = new \Behat\Mink\Session($this->driver);
    }

    function getURL(URLToCheck $urlToCheck) {
        $fullURL = $this->siteURL.$urlToCheck->getUrl();

        $this->session->start();  // start session:
        $this->session->visit($fullURL);  // open some page in browser:
        $status = 200; //TODO - do this properly

        $this->urlsChecked[] = new URLResult(
            $urlToCheck->getUrl(),
            $status,
            $urlToCheck->getReferrer(),
            substr($this->session->getPage()->getContent(), 0, 200)
        );

    }

//    function parseLinkResult(DOMElement $element, $referrer)  {
//        $href = $element->getAttribute('href');
//
//        if (strpos($href, '/') === 0) {
//            if (array_key_exists($href, $this->urlsToCheck) == false) {
//                $this->urlsToCheck[$href] = new URLToCheck($href, $referrer);;
//            }
//        }
//    }

//    function parseImgResult(DOMElement $element, $referrer)  {
//        $href = $element->getAttribute('src');
//
//        if (strpos($href, '/') === 0) {
//            if (array_key_exists($href, $this->urlsChecked) == false) {
//                $this->urlsToCheck[$href] = new URLToCheck($href, $referrer);
//            }
//        }
//    }

    function checkURL($url) {
        $this->urlsToCheck[$url] = new URLToCheck($url, '/');

        $finished = false;
        while ($finished == false) {
            $finished = true;
            foreach ($this->urlsToCheck as $url => $urlToCheck) {
                if ($urlToCheck != null) {
                    $this->checkURLInternal($urlToCheck);
                    $this->urlsToCheck[$url] = null;
                    $finished = false;
                }
            }
        }
        echo "\n";
    }



    function getContentType() {

        $responseHeaders = $this->session->getResponseHeaders();

        foreach ($responseHeaders as $key => $value) {

            if(strcasecmp($key, 'Content-Type') === 0) {                
                if (is_array($value) == false || (count($value) != 1) ){
                    throw new \Exception("Single Content-type header not set ".var_export($value, true));
                }

                $contentType = $value[0];
                $colonPosition = strpos($contentType, ';');
        
                if ($colonPosition !== false) {
                    return substr($contentType, 0, $colonPosition);
                }

                return $contentType;
            }
        }

        throw new \Exception('Content-Type header not set.');
    }


    function checkURLInternal(URLToCheck $urlToCheck) {

        echo ".";

//        if ($this->count > 30) {
//        echo "\n";
//            return;
//        }
        
        $this->count++;
        if (($this->count%30) == 0) {
            echo "\n";
        }
        


        $path = $urlToCheck->getUrl();

        try {
            $this->session->start();  // start session:
            $this->session->visit($this->siteURL.$path);  // open some page in browser:

//            if (false) {
//                echo $session->getCurrentUrl();  // get the current page URL:
//                echo $session->getStatusCode();  // get the response status code:
//                echo substr($session->getPage()->getContent(), 0, 500);// get page content:
//            }


            $contentType = $this->getContentType();




            switch($contentType) {

                case ('text/html'): {
                    $page = $this->session->getPage();
                    break;
                }


                case ('application/x-zip-compressed'):
                case ('application/x-shockwave-flash'):
                case ('application/pdf') :
                    //Don't process these.
                    
                case ('image/gif') :
                case ('image/jpeg') :
                case ('image/jpg') :
                case ('image/png') : {
                    //echo "Image with status - $status\n";
                    return;
                }

                default: {
                    throw new \Exception("Unrecognised content-type $contentType");
                }
            }

            $status = $this->session->getStatusCode();
            $this->urlsChecked[] = new URLResult(
                $urlToCheck->getUrl(),
                $status,
                $urlToCheck->getReferrer(),
                substr($page->getText(), 0, 200)
            );

            $elementsByCss = $page->findAll('css', 'a');

            foreach ($elementsByCss as $element) {
                /** @var  \Behat\Mink\Element\NodeElement $element */
                if ($element->hasAttribute('href')) {
                    $link = $element->getAttribute('href');

                    if (strpos($link, '/') === 0) {
                        if (array_key_exists($link, $this->urlsToCheck) == false) {
                            $this->urlsToCheck[$link] = new URLToCheck($link, $path);
                        }
                    }
                }
            }
        }
        catch(\Guzzle\Http\Exception\CurlException $ce) {
            $this->urlsChecked[] = new URLResult($path, 0, "CurlException getting $path - ".$ce->getMessage());
        }
        catch(\Exception $e) {
            $this->urlsChecked[] = new URLResult($path, 500, "Error getting $path - ".$e->getMessage(). " Exception type is ".get_class($e));
        }
    }

    function getResults() {
        return $this->urlsChecked;
    }
}



 