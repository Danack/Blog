<?php

declare(strict_types=1);

namespace BaseReality\Service;

class DeploySiteNotifier
{

    public static $knownFullnames = [
//        "Danack/BaseReality",
        "Danack/Blog",
        "Danack/docs",
        "Danack/example",
//        "Danack/Imagick-demos",
        "Imagick/ImagickDemos",
        "Danack/OpenSourceFees",
        "Danack/TierJigDocs",
        "PHPOpenDocs/PHPOpenDocs",
        "PHPOpenDocs/Sadness",
    ];


    public function pushBuildNotification($fullName, $type)
    {
        if (in_array($fullName, DeploySiteNotifier::$knownFullnames, true) === true) {
            $filename = preg_replace('#[^a-zA-Z0-9]#iu', '', $fullName) . '.json';
            $fullFilename = __DIR__ . '/../../../var/github_push/' . $filename;
            $data = [
                'event' => $type,
                'name' => $fullName
            ];

            @mkdir(dirname($fullFilename), 0755, true);
            file_put_contents($fullFilename, json_encode($data, JSON_PRETTY_PRINT));
        }
    }

}
