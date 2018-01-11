<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Assertion;

/**
 * Sorting items is a way how to use Matej to deliver personalized experience to users.
 * It allows to sort given list of items according to the user preference.
 */
class Sorting extends AbstractCommand implements UserAwareInterface
{
    /** @var string */
    private $userId;
    /** @var string[] */
    private $itemIds = [];

    private function __construct($userId, array $itemIds)
    {
        $this->setUserId($userId);
        $this->setItemIds($itemIds);
    }

    /**
     * Sort given item ids for user-based recommendations.
     *
     * @param mixed $userId
     * @param array $itemIds
     * @return static
     */
    public static function create($userId, array $itemIds)
    {
        return new static($userId, $itemIds);
    }

    public function getUserId()
    {
        return $this->userId;
    }

    protected function setUserId($userId)
    {
        Assertion::typeIdentifier($userId);
        $this->userId = $userId;
    }

    protected function setItemIds(array $itemIds)
    {
        Assertion::allTypeIdentifier($itemIds);
        $this->itemIds = $itemIds;
    }

    protected function getCommandType()
    {
        return 'sorting';
    }

    protected function getCommandParameters()
    {
        return ['user_id' => $this->userId, 'item_ids' => $this->itemIds];
    }
}
