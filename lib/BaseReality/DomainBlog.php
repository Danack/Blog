<?php

namespace BaseReality;

use Intahwebz\Request;

class DomainBlog implements \Intahwebz\Domain
{
    public function __construct()
    {
    }

    public function getContentDomain($contentID)
    {
        $domainInfo = $this->getDomainInfo();

        $domainName = $domainInfo->canonicalDomain;

        if (CDN_ENABLED == true) {
            $cdnID = ($contentID % CDN_CNAMES) + 1;
            $domainName = "cdn".$cdnID.".".$domainName;
        }

        $scheme = $domainInfo->currentScheme;

        return $scheme.'://'.$domainName;
    }


    /**
     * @return \Intahwebz\DomainInfo
     */
    public function getDomainInfo()
    {
        $currentDomain = "blog.basereality.com";//$this->request->getHostName();

        $canonicalDomain = $currentDomain;

        if (mb_stripos($currentDomain, 'blog.') !== 0) {
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

        return $domainInfo;
    }

    public function getURLForCurrentDomain($path, $secure = false)
    {
        $domainInfo = $this->getDomainInfo();

        $scheme = $domainInfo->currentScheme;

        if ($secure) {
            $scheme = 'https';
        }

        $fullURL = $scheme.'://'.$domainInfo->canonicalDomain.$path;

        return $fullURL;
    }
}
