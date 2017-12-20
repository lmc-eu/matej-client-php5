<?php

namespace Lmc\Matej\Http;

use Lmc\Matej\Model\Response;
use Psr\Http\Message\ResponseInterface;

interface ResponseDecoderInterface
{
    public function decode(ResponseInterface $httpResponse, $responseClass = Response::class);
}
