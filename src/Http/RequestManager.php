<?php

namespace Lmc\Matej\Http;

use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Lmc\Matej\Http\Plugin\ExceptionPlugin;
use Lmc\Matej\Matej;
use Lmc\Matej\Model\Request;
use Lmc\Matej\Model\Response;

/**
 * Encapsulates HTTP layer, ie. request/response handling.
 * This class should not be typically used directly - its supposed to be called internally from `Matej` class.
 */
class RequestManager
{
    const CLIENT_VERSION_HEADER = 'Matej-Client-Version';
    const REQUEST_ID_HEADER = 'Matej-Request-Id';
    const RESPONSE_ID_HEADER = 'Matej-Response-Id';
    /** @var string */
    private $baseUrl = 'https://%s.matej.lmc.cz';
    /** @var string */
    protected $accountId;
    /** @var string */
    protected $apiKey;
    /** @var HttpClient */
    protected $httpClient;
    /** @var MessageFactory */
    protected $messageFactory;
    /** @var ResponseDecoderInterface */
    protected $responseDecoder;

    public function __construct($accountId, $apiKey)
    {
        $this->accountId = $accountId;
        $this->apiKey = $apiKey;
    }

    public function sendRequest(Request $request)
    {
        $httpRequest = $this->createHttpRequestFromMatejRequest($request);
        $client = $this->createConfiguredHttpClient();
        $httpResponse = $client->sendRequest($httpRequest);

        return $this->getResponseDecoder()->decode($httpResponse);
    }

    /** @codeCoverageIgnore */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /** @codeCoverageIgnore */
    public function setMessageFactory(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;
    }

    /** @codeCoverageIgnore */
    public function setResponseDecoder(ResponseDecoderInterface $responseDecoder)
    {
        $this->responseDecoder = $responseDecoder;
    }

    /** @codeCoverageIgnore */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    protected function getHttpClient()
    {
        if ($this->httpClient === null) {
            // @codeCoverageIgnoreStart
            $this->httpClient = HttpClientDiscovery::find();
            // @codeCoverageIgnoreEnd
        }

        return $this->httpClient;
    }

    protected function getMessageFactory()
    {
        if ($this->messageFactory === null) {
            $this->messageFactory = MessageFactoryDiscovery::find();
        }

        return $this->messageFactory;
    }

    protected function getResponseDecoder()
    {
        if ($this->responseDecoder === null) {
            $this->responseDecoder = new ResponseDecoder();
        }

        return $this->responseDecoder;
    }

    protected function createConfiguredHttpClient()
    {
        return new PluginClient($this->getHttpClient(), [new HeaderSetPlugin($this->getDefaultHeaders()), new AuthenticationPlugin(new HmacAuthentication($this->apiKey)), new ExceptionPlugin()]);
    }

    protected function createHttpRequestFromMatejRequest(Request $request)
    {
        $requestBody = json_encode($request->getData());
        $uri = $this->buildBaseUrl() . $request->getPath();

        return $this->getMessageFactory()->createRequest($request->getMethod(), $uri, ['Content-Type' => 'application/json', self::REQUEST_ID_HEADER => $request->getRequestId()], $requestBody);
    }

    protected function buildBaseUrl()
    {
        return sprintf($this->baseUrl, $this->accountId);
    }

    private function getDefaultHeaders()
    {
        return [self::CLIENT_VERSION_HEADER => Matej::CLIENT_ID . '/' . Matej::VERSION];
    }
}
