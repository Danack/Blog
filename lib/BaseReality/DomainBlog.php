<?php

namespace BaseReality;


use Intahwebz\Request;

class DomainBlog implements \Intahwebz\Domain {

    private $request;

    function __construct(Request $request) {
        $this->request = $request;
    }

    function getContentDomain($contentID) {

        $domainInfo = $this->getDomainInfo();

        $domainName = $domainInfo->canonicalDomain;

        if(CDN_ENABLED == TRUE){
            $cdnID = ($contentID % CDN_CNAMES) + 1;
            $domainName = "cdn".$cdnID.".".$domainName;
        }

        $scheme = $domainInfo->currentScheme;

        return $scheme.'://'.$domainName;
    }


    /**
     * @return \Intahwebz\DomainInfo
     */
    public function getDomainInfo() {
        $currentDomain = $this->request->getHostName();

        $canonicalDomain = $currentDomain;

        if(mb_stripos($currentDomain, 'blog.') !== 0){
            $canonicalDomain = 'blog.'.$canonicalDomain;
        }

        $currentURL = false;

        $domainInfo = new \Intahwebz\DomainInfo(
            $currentDomain,
            ROOT_DOMAIN,
            $canonicalDomain,
            $this->request->getScheme(),
            true,
            $currentURL
        );

        return	$domainInfo;
    }

    function getURLForCurrentDomain($path, $secure = FALSE){
        $domainInfo = $this->getDomainInfo();

        $scheme = $domainInfo->currentScheme;

        if ($secure) {
            $scheme = 'https';
        }

        $fullURL = $scheme.'://'.$domainInfo->canonicalDomain.$path;

        return $fullURL;
    }

}
 