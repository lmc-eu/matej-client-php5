<?php

namespace Lmc\Matej;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Lmc\Matej\Http\RequestManager;
use Lmc\Matej\Http\ResponseDecoderInterface;
use Lmc\Matej\RequestBuilder\RequestBuilderFactory;

class Matej
{
    const CLIENT_ID = 'php-client';
    const VERSION = '0.0.1';
    /** @var string */
    private $accountId;
    /** @var string */
    private $apiKey;
    /** @var RequestManager */
    private $requestManager;

    public function __construct($accountId, $apiKey)
    {
        $this->accountId = $accountId;
        $this->apiKey = $apiKey;
        $this->requestManager = new RequestManager($accountId, $apiKey);
    }

    public function request()
    {
        return new RequestBuilderFactory($this->getRequestManager());
    }

    public function setHttpClient(HttpClient $client)
    {
        $this->getRequestManager()->setHttpClient($client);

        return $this;
    }

    /** @codeCoverageIgnore */
    public function setHttpMessageFactory(MessageFactory $messageFactory)
    {
        $this->getRequestManager()->setMessageFactory($messageFactory);

        return $this;
    }

    /** @codeCoverageIgnore */
    public function setHttpResponseDecoder(ResponseDecoderInterface $responseDecoder)
    {
        $this->getRequestManager()->setResponseDecoder($responseDecoder);

        return $this;
    }

    protected function getRequestManager()
    {
        return $this->requestManager;
    }
}
