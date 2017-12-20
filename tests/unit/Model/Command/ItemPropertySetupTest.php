<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Exception\DomainException;
use PHPUnit\Framework\TestCase;

class ItemPropertySetupTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideConstructorName
     * @param mixed $constructorName
     * @param mixed $expectedPropertyType
     */
    public function shouldBeInstantiableViaNamedConstructors($constructorName, $expectedPropertyType)
    {
        $propertyName = 'examplePropertyName';
        /** @var ItemPropertySetup $command */
        $command = ItemPropertySetup::$constructorName($propertyName);
        $this->assertInstanceOf(ItemPropertySetup::class, $command);
        $this->assertSame(['type' => 'item-properties-setup', 'parameters' => ['property_name' => 'examplePropertyName', 'property_type' => $expectedPropertyType]], $command->jsonSerialize());
    }

    /**
     * @test
     * @dataProvider provideConstructorName
     * @param mixed $constructorName
     */
    public function shouldNotAllowItemIdAsPropertyName($constructorName)
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Cannot manipulate with property "item_id" - it is used by Matej to identify items.');
        ItemPropertySetup::$constructorName('item_id');
    }

    /**
     * @return array[]
     */
    public function provideConstructorName()
    {
        return [['int', ItemPropertySetup::PROPERTY_TYPE_INT], ['double', ItemPropertySetup::PROPERTY_TYPE_DOUBLE], ['string', ItemPropertySetup::PROPERTY_TYPE_STRING], ['boolean', ItemPropertySetup::PROPERTY_TYPE_BOOLEAN], ['timestamp', ItemPropertySetup::PROPERTY_TYPE_TIMESTAMP], ['set', ItemPropertySetup::PROPERTY_TYPE_SET]];
    }
}
