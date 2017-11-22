<?php

namespace Lmc\Matej;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function createJsonResponseFromFile($fileName)
    {
        $jsonData = file_get_contents($fileName);
        $response = new Response(StatusCodeInterface::STATUS_OK, ['Content-Type' => 'application/json'], $jsonData);

        return $response;
    }
}
