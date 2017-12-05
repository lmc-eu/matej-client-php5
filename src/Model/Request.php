<?php

namespace Lmc\Matej\Model;

use Ramsey\Uuid\Uuid;

/**
 * Represents request to Matej prepared to be executed by `RequestManager`.
 */
class Request
{
    /** @var string */
    private $path;
    /** @var string */
    private $method;
    /** @var array */
    private $data;
    /** @var string */
    private $requestId;

    public function __construct($path, $method, array $data, $requestId = null)
    {
        $this->path = $path;
        $this->method = $method;
        $this->data = $data;
        $this->requestId = isset($requestId) ? $requestId : Uuid::uuid4()->toString();
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }
}
