<?php

namespace Lmc\Matej\Model\Response;

use Lmc\Matej\Model\Response;

class SortingResponse extends Response
{
    public function getInteraction()
    {
        return $this->getCommandResponse(0);
    }

    public function getUserMerge()
    {
        return $this->getCommandResponse(1);
    }

    public function getSorting()
    {
        return $this->getCommandResponse(2);
    }
}
