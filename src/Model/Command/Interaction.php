<?php

namespace Lmc\Matej\Model\Command;

use ArrayObject;
use Lmc\Matej\Model\Assertion;

/**
 * Interaction command allows to send one interaction between a user and item.
 * When given user or item identifier is unknown, Matej will create such user or item respectively.
 */
class Interaction extends AbstractCommand implements UserAwareInterface
{
    const DEFAULT_ITEM_ID_ALIAS = 'item_id';
    /** @var string */
    private $interactionType;
    /** @var string */
    private $userId;
    /** @var string */
    private $itemId;
    /** @var string */
    private $itemIdAlias;
    /** @var int */
    private $timestamp;
    /** @var ArrayObject */
    private $attributes;

    private function __construct($interactionType, $userId, $itemIdAlias, $itemId, $timestamp = null)
    {
        $this->attributes = new ArrayObject();
        $this->setInteractionType($interactionType);
        $this->setUserId($userId);
        $this->setItemIdAlias($itemIdAlias);
        $this->setItemId($itemId);
        $this->setTimestamp(isset($timestamp) ? $timestamp : time());
    }

    /**
     * Construct Interaction between user and item identified by ID.
     * @param mixed $interactionType
     * @param mixed $userId
     * @param mixed $itemId
     * @param null|mixed $timestamp
     */
    public static function withItem($interactionType, $userId, $itemId, $timestamp = null)
    {
        $interaction = new static($interactionType, $userId, self::DEFAULT_ITEM_ID_ALIAS, $itemId, $timestamp);

        return $interaction;
    }

    /**
     * Construct Interaction between user and item identified by aliased ID.
     * @param mixed $interactionType
     * @param mixed $userId
     * @param mixed $itemIdAlias
     * @param mixed $itemId
     * @param null|mixed $timestamp
     */
    public static function withAliasedItem($interactionType, $userId, $itemIdAlias, $itemId, $timestamp = null)
    {
        return new static($interactionType, $userId, $itemIdAlias, $itemId, $timestamp);
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getCommandType()
    {
        return 'interaction';
    }

    /**
     * Set all Interaction attributes. All previously set attributes are removed.
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = new ArrayObject();
        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        return $this;
    }

    /**
     * Set Interaction attribute and its value. If attribute with the same name
     * already exists, it's replaced.
     *
     * @param mixed $value
     * @param mixed $name
     */
    public function setAttribute($name, $value)
    {
        Assertion::typeIdentifier($name);
        $this->attributes[$name] = $value;

        return $this;
    }

    public function getCommandParameters()
    {
        return ['interaction_type' => $this->interactionType, 'user_id' => $this->userId, 'timestamp' => $this->timestamp, 'attributes' => $this->attributes, $this->itemIdAlias => $this->itemId];
    }

    protected function setInteractionType($interactionType)
    {
        Assertion::typeIdentifier($interactionType);
        $this->interactionType = $interactionType;
    }

    protected function setUserId($userId)
    {
        Assertion::typeIdentifier($userId);
        $this->userId = $userId;
    }

    protected function setItemId($itemId)
    {
        Assertion::typeIdentifier($itemId);
        $this->itemId = $itemId;
    }

    protected function setItemIdAlias($itemIdAlias)
    {
        Assertion::typeIdentifier($itemIdAlias);
        $this->itemIdAlias = $itemIdAlias;
    }

    /**
     * @param int $timestamp
     */
    protected function setTimestamp($timestamp)
    {
        Assertion::integer($timestamp);
        Assertion::greaterThan($timestamp, 0);
        $this->timestamp = $timestamp;
    }
}
