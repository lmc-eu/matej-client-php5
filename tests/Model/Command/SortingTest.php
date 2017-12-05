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
        $this->assertSortingCommand($command, $userId, $itemIds);
    }

    /**
     * Execute asserts against user merge command
     * @param Sorting $command
     * @param mixed $userId
     * @param array $itemIds
     */
    private function assertSortingCommand($command, $userId, array $itemIds)
    {
        $this->assertInstanceOf(Sorting::class, $command);
        $this->assertSame(['type' => 'sorting', 'parameters' => ['user_id' => $userId, 'item_ids' => $itemIds]], $command->jsonSerialize());
        $this->assertSame($userId, $command->getUserId());
    }
}
