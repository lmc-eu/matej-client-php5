<?php

namespace Lmc\Matej\Model\Command;

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
        // TODO: assert itemId format
        $this->itemId = $itemId;
        $this->properties = $properties;
    }

    public static function create($itemId, array $properties = [])
    {
        return new static($itemId, $properties);
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
