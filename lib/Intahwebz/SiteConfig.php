<?php

namespace Intahwebz;

use BaseReality\AutogenPath;
use Psr\Log\LoggerInterface;


class SiteConfig {

    private $configFilename;
    
    private $config = array();

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Intahwebz\ObjectCache
     */
    private $objectCache;

    function __construct(
        AutogenPath $autogenPath, 
        LoggerInterface $logger,
        ObjectCache $objectCache 
    ) {
        $this->configFilename = $autogenPath->getSafePath('/', 'config.json');
        $this->logger = $logger;
        $this->objectCache = $objectCache;
        $this->readConfig();
    }

    function getVariable($variableName, $default = null) {
        if (array_key_exists($variableName, $this->config) == true) {
            return $this->config[$variableName];
        }
        return $default;
    }
    
    function getAllConfig() {
        return $this->config;
    }
    
    function setConfigVariable() {
        throw new \Exception("Not implemented");
    }
    
    function setConfig($data) {
        $this->config = array_merge($this->config, $data);
    }

    function writeConfig() {
        $json = json_encode($this->config, JSON_PRETTY_PRINT);
        $saved = file_put_contents($this->configFilename, $json);
        
        if ($saved == false) {
            $this->logger->emergency("Failed to update site config file.");
        }
        $this->objectCache->put('SiteConfig', $this->config, 60);
    }

    function readConfig() {
        $config = $this->objectCache->get('SiteConfig');
        
        if ($config == null) {
            $string = @file_get_contents($this->configFilename);

            if ($string == false) {
                return;
            }

            $config = json_decode($string, true);
            $this->objectCache->put('SiteConfig', $config, 60);
        }
        
        $this->config = $config;
    }
}




 