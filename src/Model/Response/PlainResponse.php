<?php

namespace Lmc\Matej\Model\Response;

use Lmc\Matej\Model\Response;

/**
 * Response for endpoints always returning data for only one command.
 */
class PlainResponse extends Response
{
    public function getData()
    {
        return $this->getCommandResponse(0)->getData();
    }

    public function getMessage()
    {
        return $this->getCommandResponse(0)->getMessage();
    }

    public function getStatus()
    {
        return $this->getCommandResponse(0)->getStatus();
    }
}
