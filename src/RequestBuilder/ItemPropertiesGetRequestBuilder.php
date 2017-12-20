<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Model\Request;
use Lmc\Matej\Model\Response\ItemPropertiesListResponse;

/**
 * @method ItemPropertiesListResponse send()
 */
class ItemPropertiesGetRequestBuilder extends AbstractRequestBuilder
{
    const ENDPOINT_PATH = '/item-properties';

    public function build()
    {
        return new Request(self::ENDPOINT_PATH, RequestMethodInterface::METHOD_GET, [], $this->requestId, ItemPropertiesListResponse::class);
    }
}
