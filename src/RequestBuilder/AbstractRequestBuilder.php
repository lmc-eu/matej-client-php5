<?php

namespace Lmc\Matej\RequestBuilder;

use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Http\RequestManager;
use Lmc\Matej\Model\Request;

/**
 * Request builders provides methods for simple and type-safe assembly of request to specific Matej endpoint.
 *
 * If `RequestManager` is injected to the builder via `setRequestManager()`, the request could be executed right from
 * the builder using `send()` method.
 */
abstract class AbstractRequestBuilder
{
    /** @var RequestManager */
    protected $requestManager;

    /**
     * Use Commands and other settings which were passed to this builder object to build instance of
     * Lmc\Matej\Model\Request.
     */
    abstract public function build();

    /**
     * If instance of RequestManager is injected to this builder object, you can build and send the request directly
     * via send() method of the builder itself.
     * @param RequestManager $requestManager
     */
    public function setRequestManager(RequestManager $requestManager)
    {
        $this->requestManager = $requestManager;

        return $this;
    }

    public function send()
    {
        $this->assertRequestManagerIsAvailable();

        return $this->requestManager->sendRequest($this->build());
    }

    private function assertRequestManagerIsAvailable()
    {
        if ($this->requestManager === null) {
            throw new LogicException('Instance of RequestManager must be set to request builder via setRequestManager() before' . ' calling send()');
        }
    }
}
