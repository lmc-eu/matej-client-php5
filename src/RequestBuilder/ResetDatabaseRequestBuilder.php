<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Model\Request;
use Lmc\Matej\Model\Response\PlainResponse;

/**
 * @method PlainResponse send()
 */
class ResetDatabaseRequestBuilder extends AbstractRequestBuilder
{
    const ENDPOINT_PATH = '/database';

    public function build()
    {
        return new Request(self::ENDPOINT_PATH, RequestMethodInterface::METHOD_DELETE, [], $this->requestId, PlainResponse::class);
    }
}
