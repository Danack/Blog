<?php


namespace BaseReality\Response;

use Danack\Response\StubResponse;

class UserErrorResponse implements StubResponse
{
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getStatus()
    {
        return 400;
    }

    public function getBody()
    {
        return $this->message;
    }

    public function getHeaders()
    {
        return [];
    }
}
