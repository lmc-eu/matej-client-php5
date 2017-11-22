<?php

namespace Lmc\Matej\Model\Command;

use PHPUnit\Framework\TestCase;

class ItemPropertyTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideProperties
     * @param array $properties
     * @param array $expectedParameters
     */
    public function shouldBeInstantiableViaNamedConstructor(array $properties, array $expectedParameters)
    {
        $command = ItemProperty::create('exampleItemId', $properties);
        $this->assertInstanceOf(ItemProperty::class, $command);
        $this->assertSame(['type' => 'item-properties', 'parameters' => $expectedParameters], $command->jsonSerialize());
    }

    /**
     * @return array[]
     */
    public function provideProperties()
    {
        return ['No item properties' => [[], ['item_id' => 'exampleItemId']], 'One item property' => [['date' => 1510756952], ['date' => 1510756952, 'item_id' => 'exampleItemId']], 'Multiple item properties' => [['item1' => 'value1', 'item2' => 'value2'], ['item1' => 'value1', 'item2' => 'value2', 'item_id' => 'exampleItemId']], 'Should not allow to override item_id' => [['item_id' => 'customItemId'], ['item_id' => 'exampleItemId']]];
    }
}
