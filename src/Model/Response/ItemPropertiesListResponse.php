<?php

namespace Lmc\Matej\Model\Response;

use Lmc\Matej\Model\Response;

class ItemPropertiesListResponse extends Response
{
    public function isSuccessful()
    {
        return $this->getCommandResponse(0)->isSuccessful();
    }

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
