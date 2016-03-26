<?php

namespace Blog;



class Config
{    
    const GITHUB_ACCESS_TOKEN = 'github.access_token';
    const GITHUB_REPO_NAME = 'github.repo_name';

    const LIBRATO_KEY = 'librato.key';
    const LIBRATO_USERNAME = 'librato.username';
    const LIBRATO_STATSSOURCENAME = 'librato.stats_source_name';

    const JIG_COMPILE_CHECK = 'jig.compilecheck';

    const DOMAIN_CANONICAL = 'domain.canonical';
    const DOMAIN_CDN_PATTERN= 'domain.cdn.pattern';
    const DOMAIN_CDN_TOTAL= 'domain.cdn.total';

    const CACHING_SETTING = 'caching.setting';
    
    const SCRIPT_VERSION = 'script.version';
    const SCRIPT_PACKING = 'script.packing';

    const REPOSITORY_MAPPING = 'repo.mapping';
    const REPOSITORY_MAPPING_SQL = 'repo.mapping.sql';
    const REPOSITORY_MAPPING_STUB = 'repo.mapping.stub';

    const KEYS_LOADER = 'keys_loader';
    const KEYS_LOADER_NONE = 'keys_loader.none';
    const KEYS_LOADER_CLAVIS = 'keys_loader.clavis';

    private $values = [];

    public function __construct()
    {
        $this->values = [];
        $this->values = array_merge($this->values, \getAppEnv());
        
        if ($this->values[Config::KEYS_LOADER] == self::KEYS_LOADER_CLAVIS) {
            require __DIR__."/../../../clavis.php";
            $this->values = array_merge($this->values, getAppKeys());
        }
    }

    public function getKey($key)
    {
        if (array_key_exists($key, $this->values) == false) {
            throw new \Exception("Missing config value of $key");
        }

        return $this->values[$key];
    }

    public function getKeyWithDefault($key, $default)
    {
        if (array_key_exists($key, $this->values) === false) {
            return $default;
        }

        return $this->values[$key];
    }
}
