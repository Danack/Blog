<?php

declare(strict_types=1);

namespace BaseReality\Params;

use BaseReality\Service\DeploySiteNotifier;
use Params\Rule\Enum;
use Params\Rule\GetString;
use Params\Rule\PositiveInt;
use VarMap\VarMap;
use Params\Rule\GetInt;
use Params\SafeAccess;
use Params\CreateOrErrorFromVarMap;
use Params\Rule\MinLength;
use Params\Rule\MaxLength;

class SiteBuildParams
{
    use SafeAccess;
    use CreateOrErrorFromVarMap;

    /** @var string */
    private $site_name;

    /** @var string */
    private $commit_sha;

    /**
     *
     * @param string $site_name
     * @param string $commit_sha
     */
    public function __construct(string $site_name, string $commit_sha)
    {
        $this->site_name = $site_name;
        $this->commit_sha = $commit_sha;
    }

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->site_name;
    }

    /**
     * @return string
     */
    public function getCommitSha(): string
    {
        return $this->commit_sha;
    }

    /**
     * @param VarMap $variableMap
     * @return array
     */
    public static function getRules(VarMap $variableMap)
    {
        return [
            'site_name' => [
                new GetString($variableMap),
                new Enum(DeploySiteNotifier::$knownFullnames),
            ],
            'commit_sha' => [
                new GetString($variableMap),
                new MinLength(8),
                new MaxLength(64),
            ],
        ];
    }
}
