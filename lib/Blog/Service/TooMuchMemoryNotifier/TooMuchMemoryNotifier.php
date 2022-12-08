<?php

declare(strict_types = 1);

namespace Blog\Service\TooMuchMemoryNotifier;

use Psr\Http\Message\ServerRequestInterface as Request;

interface TooMuchMemoryNotifier
{
    public function tooMuchMemory(Request $request): void;
}
