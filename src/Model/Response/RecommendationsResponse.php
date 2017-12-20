<?php

namespace Lmc\Matej\Model\Response;

use Lmc\Matej\Model\Response;

class RecommendationsResponse extends Response
{
    public function getInteraction()
    {
        return $this->getCommandResponse(0);
    }

    public function getUserMerge()
    {
        return $this->getCommandResponse(1);
    }

    public function getRecommendation()
    {
        return $this->getCommandResponse(2);
    }
}
