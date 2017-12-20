<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Model\Command\Interaction;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserMerge;
use Lmc\Matej\Model\Request;
use Lmc\Matej\Model\Response\SortingResponse;

/**
 * @method SortingResponse send()
 */
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
        $this->assertConsistentUsersInCommands();

        return new Request(self::ENDPOINT_PATH, RequestMethodInterface::METHOD_POST, [$this->interactionCommand, $this->userMergeCommand, $this->sortingCommand], $this->requestId, SortingResponse::class);
    }

    private function assertConsistentUsersInCommands()
    {
        $mainCommandUser = $this->sortingCommand->getUserId();
        if ($this->interactionCommand !== null && $mainCommandUser !== $this->interactionCommand->getUserId()) {
            throw LogicException::forInconsistentUserId($this->sortingCommand, $this->interactionCommand);
        }
        if ($this->userMergeCommand !== null && $mainCommandUser !== $this->userMergeCommand->getUserId()) {
            throw LogicException::forInconsistentUserId($this->sortingCommand, $this->userMergeCommand);
        }
    }
}
