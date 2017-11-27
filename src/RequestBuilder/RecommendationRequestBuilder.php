<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Model\Command\Interaction;
use Lmc\Matej\Model\Command\UserMerge;
use Lmc\Matej\Model\Command\UserRecommendation;
use Lmc\Matej\Model\Request;

class RecommendationRequestBuilder extends AbstractRequestBuilder
{
    const ENDPOINT_PATH = '/recommendations';
    /** @var Interaction|null */
    private $interactionCommand;
    /** @var UserMerge|null */
    private $userMergeCommand;
    /** @var UserRecommendation */
    private $userRecommendationCommand;

    public function __construct(UserRecommendation $userRecommendationCommand)
    {
        $this->userRecommendationCommand = $userRecommendationCommand;
    }

    public function setUserMerge(UserMerge $merge)
    {
        $this->userMergeCommand = $merge;

        return $this;
    }

    public function setInteraction(Interaction $interaction)
    {
        $this->interactionCommand = $interaction;

        return $this;
    }

    public function build()
    {
        return new Request(self::ENDPOINT_PATH, RequestMethodInterface::METHOD_POST, [$this->interactionCommand, $this->userMergeCommand, $this->userRecommendationCommand]);
    }
}
