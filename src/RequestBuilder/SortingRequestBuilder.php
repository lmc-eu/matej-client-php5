<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Model\Command\Interaction;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserMerge;
use Lmc\Matej\Model\Request;

class SortingRequestBuilder extends AbstractRequestBuilder
{
    const ENDPOINT_PATH = '/sorting';
    /** @var Interaction|null */
    private $interactionCommand;
    /** @var UserMerge|null */
    private $userMergeCommand;
    /** @var Sorting */
    private $sortingCommand;

    public function __construct(Sorting $sortingCommand)
    {
        $this->sortingCommand = $sortingCommand;
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
        return new Request(self::ENDPOINT_PATH, RequestMethodInterface::METHOD_POST, [$this->interactionCommand, $this->userMergeCommand, $this->sortingCommand]);
    }
}
