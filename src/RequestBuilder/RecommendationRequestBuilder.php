<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Model\Command\Interaction;
use Lmc\Matej\Model\Command\UserMerge;
use Lmc\Matej\Model\Command\UserRecommendation;
use Lmc\Matej\Model\Request;
use Lmc\Matej\Model\Response\RecommendationsResponse;

/**
 * @method RecommendationsResponse send()
 */
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

    /** @return $this */
    public function setUserMerge(UserMerge $merge)
    {
        $this->userMergeCommand = $merge;

        return $this;
    }

    /** @return $this */
    public function setInteraction(Interaction $interaction)
    {
        $this->interactionCommand = $interaction;

        return $this;
    }

    public function build()
    {
        $this->assertInteractionUserId();
        $this->assertUserMergeUserId();

        return new Request(static::ENDPOINT_PATH, RequestMethodInterface::METHOD_POST, [$this->interactionCommand, $this->userMergeCommand, $this->userRecommendationCommand], $this->requestId, RecommendationsResponse::class);
    }

    /**
     * Assert that interaction user ids are ok:
     * - (A,  null,  A)
     * - (A, A -> ?, ?)
     */
    private function assertInteractionUserId()
    {
        if ($this->interactionCommand === null) {
            return;
        }
        $interactionUserId = $this->interactionCommand->getUserId();
        // (A, null, A)
        if ($this->userMergeCommand === null && $interactionUserId !== $this->userRecommendationCommand->getUserId()) {
            throw LogicException::forInconsistentUserId($this->userRecommendationCommand, $this->interactionCommand);
        }
        // (A, A -> ?, ?)
        if ($this->userMergeCommand !== null && $interactionUserId !== $this->userMergeCommand->getSourceUserId()) {
            throw LogicException::forInconsistentUserMergeAndInteractionCommand($this->userMergeCommand->getSourceUserId(), $interactionUserId);
        }
    }

    /**
     * Assert user merge id is ok:
     * (?, ? -> A, A)
     */
    private function assertUserMergeUserId()
    {
        if ($this->userMergeCommand !== null && $this->userMergeCommand->getUserId() !== $this->userRecommendationCommand->getUserId()) {
            throw LogicException::forInconsistentUserId($this->userRecommendationCommand, $this->userMergeCommand);
        }
    }
}
