<?php

namespace Lmc\Matej;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Lmc\Matej\Http\RequestManager;
use Lmc\Matej\Http\ResponseDecoderInterface;
use Lmc\Matej\RequestBuilder\RequestBuilderFactory;

class Matej
{
    const CLIENT_ID = 'php5-client';
    const VERSION = '2.3.1';
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

    /** @return $this */
    public function setHttpClient(HttpClient $client)
    {
        $this->getRequestManager()->setHttpClient($client);

        return $this;
    }

    /**
     * @internal used mainly in integration tests
     * @param mixed $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->getRequestManager()->setBaseUrl($baseUrl);

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @param MessageFactory $messageFactory
     * @return $this
     */
    public function setHttpMessageFactory(MessageFactory $messageFactory)
    {
        $this->getRequestManager()->setMessageFactory($messageFactory);

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @param ResponseDecoderInterface $responseDecoder
     * @return $this
     */
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
