<?php

declare(strict_types=1);

namespace BaseReality\ApiController;

use Danack\Response\JsonResponse;

class HealthCheck
{
    public function get()
    {
        return new JsonResponse([
            'status' => 'ok',
            'box' => 'api'
        ]);
    }
}
