<?php

namespace Lmc\Matej\Model;

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

    public function __construct($path, $method, array $data)
    {
        $this->path = $path;
        $this->method = $method;
        $this->data = $data;
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
}
