<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\TestCase;

class SortingTest extends TestCase
{
    /** @test */
    public function shouldBeInstantiableViaNamedConstructor()
    {
        $userId = 'user-id';
        $itemIds = ['item-1', 'item-3', 'item-2'];
        $command = Sorting::create($userId, $itemIds);
        $this->assertSortingObject($command, $userId, $itemIds);
    }

    /**
     * Execute asserts against user merge object
     * @param object $object
     * @param mixed $userId
     * @param array $itemIds
     */
    private function assertSortingObject($object, $userId, array $itemIds)
    {
        $this->assertInstanceOf(Sorting::class, $object);
        $this->assertSame(['type' => 'sorting', 'parameters' => ['user_id' => $userId, 'item_ids' => $itemIds]], $object->jsonSerialize());
    }
}
