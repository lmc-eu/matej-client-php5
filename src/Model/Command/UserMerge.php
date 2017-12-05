<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Assertion;

/**
 * Take all interactions from the source user and merge them to the target user.
 * Source user will be DELETED and unknown to Matej from this action.
 */
class UserMerge extends AbstractCommand implements UserAwareInterface
{
    /** @var string */
    private $sourceUserId;
    /** @var string */
    private $targetUserId;

    private function __construct($targetUserId, $sourceUserId)
    {
        $this->setTargetUserId($targetUserId);
        $this->setSourceUserId($sourceUserId);
    }

    /**
     * Merge source user into target user AND DELETE SOURCE USER.
     * @param mixed $targetUserId
     * @param mixed $sourceUserIdToBeDeleted
     */
    public static function mergeInto($targetUserId, $sourceUserIdToBeDeleted)
    {
        return new static($targetUserId, $sourceUserIdToBeDeleted);
    }

    /**
     * Merge source user into target user AND DELETE SOURCE USER.
     * @param mixed $sourceUserIdToBeDeleted
     * @param mixed $targetUserId
     */
    public static function mergeFromSourceToTargetUser($sourceUserIdToBeDeleted, $targetUserId)
    {
        return new static($targetUserId, $sourceUserIdToBeDeleted);
    }

    public function getUserId()
    {
        return $this->targetUserId;
    }

    protected function setSourceUserId($sourceUserId)
    {
        Assertion::typeIdentifier($sourceUserId);
        $this->sourceUserId = $sourceUserId;
    }

    protected function setTargetUserId($targetUserId)
    {
        Assertion::typeIdentifier($targetUserId);
        $this->targetUserId = $targetUserId;
    }

    protected function getCommandType()
    {
        return 'user-merge';
    }

    protected function getCommandParameters()
    {
        return ['target_user_id' => $this->targetUserId, 'source_user_id' => $this->sourceUserId];
    }
}
