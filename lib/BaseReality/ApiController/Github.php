<?php

declare(strict_types=1);

namespace BaseReality\ApiController;

use BaseReality\Service\DeploySiteNotifier;
use Danack\Response\JsonResponse;
use BaseReality\Params\SiteBuildParams;
use VarMap\VarMap;
use BaseReality\Response\UserErrorResponse;
use BaseReality\Service\InputData\InputData;
use BaseReality\Service\InputData\FakeInputData;

class Github
{
    /** @var DeploySiteNotifier */
    private $deploySiteNotifier;

    /**
     *
     * @param DeploySiteNotifier $deploySiteNotifier
     */
    public function __construct(DeploySiteNotifier $deploySiteNotifier)
    {
        $this->deploySiteNotifier = $deploySiteNotifier;
    }

    /**
     * @param VarMap $varMap
     * @throws \Params\Exception\RulesEmptyException
     * @throws \Params\Exception\ValidationException
     */
    public function buildComplete(VarMap $varMap)
    {
        [$siteBuildParams, $errors] = SiteBuildParams::createOrErrorFromVarMap($varMap);
        if (count($errors) !== 0 ) {
            return new JsonResponse(
                "invalid request: " . implode(", ", $errors),
                [],
                400
            );
        }

        /** @var SiteBuildParams $siteBuildParams  */
        $this->deploySiteNotifier->pushBuildNotification(
            $siteBuildParams->getSiteName(),
            'build_complete'
        );

        $result = [
            'status' => 'ok',
            'full_name' => $siteBuildParams->getSiteName()
        ];

        return new JsonResponse($result);
    }

    public function pushNotification(InputData $inputData)
    {
        $data = $inputData->getData();

        $fullName = $data['repository']["full_name"];
        $this->deploySiteNotifier->pushBuildNotification($fullName, 'github_push');

        $result = [
            'status' => 'ok',
            'full_name' => $fullName
        ];

        return new JsonResponse($result);
    }

    public function pushNotificationFake()
    {
//        $json = \file_get_contents(__DIR__ .'/example.json');
        $json = \file_get_contents(__DIR__ .'/example2.json');
        $data = json_decode_safe($json);

        return $this->pushNotification(new FakeInputData($data));
    }
}
