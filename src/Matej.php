<?php

namespace Lmc\Matej;

class Matej
{
    /** @var string */
    private $clientId;
    /** @var string */
    private $apiKey;

    public function __construct($clientId, $apiKey)
    {
        $this->clientId = $clientId;
        $this->apiKey = $apiKey;
    }
}
