<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Assertion;

/**
 * Command to save different item content properties to Matej.
 */
class ItemProperty extends AbstractCommand
{
    /** @var string */
    private $itemId;
    /** @var array */
    private $properties;

    private function __construct($itemId, array $properties)
    {
        $this->setItemId($itemId);
        $this->setProperties($properties);
    }

    public static function create($itemId, array $properties = [])
    {
        return new static($itemId, $properties);
    }

    protected function setItemId($itemId)
    {
        Assertion::typeIdentifier($itemId);
        $this->itemId = $itemId;
    }

    protected function setProperties(array $properties)
    {
        Assertion::keyNotExists($properties, 'item_id', 'Cannot update value of "item_id" property - it is used by Matej to identify the item and cannot be altered once created.');
        $this->properties = $properties;
    }

    protected function getCommandType()
    {
        return 'item-properties';
    }

    protected function getCommandParameters()
    {
        $parameters = $this->properties;
        $parameters['item_id'] = $this->itemId;

        return $parameters;
    }
}
