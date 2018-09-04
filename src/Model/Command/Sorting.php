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
    /** @var string|null */
    private $modelName;

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

    /**
     * Set A/B model name
     *
     * @param mixed $modelName
     * @return $this
     */
    public function setModelName($modelName)
    {
        Assertion::typeIdentifier($modelName);
        $this->modelName = $modelName;

        return $this;
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
        $parameters = ['user_id' => $this->userId, 'item_ids' => $this->itemIds];
        if ($this->modelName !== null) {
            $parameters['model_name'] = $this->modelName;
        }

        return $parameters;
    }
}
