<?php

namespace Lmc\Matej\Model\Command;

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
        $command = forward_static_call([ItemPropertySetup::class, $constructorName], $propertyName);
        $this->assertInstanceOf(ItemPropertySetup::class, $command);
        $this->assertSame(['type' => 'item-properties-setup', 'parameters' => ['property_name' => 'examplePropertyName', 'property_type' => $expectedPropertyType]], $command->jsonSerialize());
    }

    /**
     * @return array[]
     */
    public function provideConstructorName()
    {
        return [['int', ItemPropertySetup::PROPERTY_TYPE_INT], ['double', ItemPropertySetup::PROPERTY_TYPE_DOUBLE], ['string', ItemPropertySetup::PROPERTY_TYPE_STRING], ['boolean', ItemPropertySetup::PROPERTY_TYPE_BOOLEAN], ['timestamp', ItemPropertySetup::PROPERTY_TYPE_TIMESTAMP], ['set', ItemPropertySetup::PROPERTY_TYPE_SET]];
    }
}
