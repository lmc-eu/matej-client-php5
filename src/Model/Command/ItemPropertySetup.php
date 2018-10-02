<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Assertion;
use Lmc\Matej\Model\Command\Constants\PropertyType;

/**
 * Command to add or delete item property in the database.
 */
class ItemPropertySetup extends AbstractCommand
{
    /** @var string */
    private $propertyName;
    /** @var PropertyType */
    private $propertyType;

    private function __construct($propertyName, PropertyType $propertyType)
    {
        $this->setPropertyName($propertyName);
        $this->propertyType = $propertyType;
    }

    /** @return static */
    public static function int($propertyName)
    {
        return new static($propertyName, PropertyType::INT());
    }

    /** @return static */
    public static function double($propertyName)
    {
        return new static($propertyName, PropertyType::DOUBLE());
    }

    /** @return static */
    public static function string($propertyName)
    {
        return new static($propertyName, PropertyType::STRING());
    }

    /** @return static */
    public static function boolean($propertyName)
    {
        return new static($propertyName, PropertyType::BOOLEAN());
    }

    /** @return static */
    public static function timestamp($propertyName)
    {
        return new static($propertyName, PropertyType::TIMESTAMP());
    }

    /** @return static */
    public static function set($propertyName)
    {
        return new static($propertyName, PropertyType::SET());
    }

    /** @return static */
    public static function geolocation($propertyName)
    {
        return new static($propertyName, PropertyType::GEOLOCATION());
    }

    protected function setPropertyName($propertyName)
    {
        Assertion::typeIdentifier($propertyName);
        Assertion::notEq($propertyName, 'item_id', 'Cannot manipulate with property "item_id" - it is used by Matej to identify items.');
        $this->propertyName = $propertyName;
    }

    protected function getCommandType()
    {
        return 'item-properties-setup';
    }

    protected function getCommandParameters()
    {
        return ['property_name' => $this->propertyName, 'property_type' => $this->propertyType->jsonSerialize()];
    }
}
