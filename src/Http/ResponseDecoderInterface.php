<?php

namespace Lmc\Matej\Http;

use Psr\Http\Message\ResponseInterface;

interface ResponseDecoderInterface
{
    public function decode(ResponseInterface $httpResponse);
}
