<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Exception\DomainException;
use Lmc\Matej\Model\Command\Constants\PropertyType;
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
        return [['int', PropertyType::INT], ['double', PropertyType::DOUBLE], ['string', PropertyType::STRING], ['boolean', PropertyType::BOOLEAN], ['timestamp', PropertyType::TIMESTAMP], ['set', PropertyType::SET]];
    }
}
