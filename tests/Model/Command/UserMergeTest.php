<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\TestCase;

class UserMergeTest extends TestCase
{
    /** @test */
    public function shouldGenerateCorrectSignature()
    {
        $sourceUserId = 'source-user';
        $targetUserId = 'target-user';
        $command = UserMerge::mergeInto($targetUserId, $sourceUserId);
        $this->assertUserMergeCommand($command, $sourceUserId, $targetUserId);
        $command = UserMerge::mergeFromSourceToTargetUser($sourceUserId, $targetUserId);
        $this->assertUserMergeCommand($command, $sourceUserId, $targetUserId);
    }

    /**
     * Execute asserts against user merge command
     * @param UserMerge $command
     * @param mixed $sourceUserId
     * @param mixed $targetUserId
     */
    private function assertUserMergeCommand($command, $sourceUserId, $targetUserId)
    {
        $this->assertInstanceOf(UserMerge::class, $command);
        $this->assertSame(['type' => 'user-merge', 'parameters' => ['target_user_id' => $targetUserId, 'source_user_id' => $sourceUserId]], $command->jsonSerialize());
        $this->assertSame($targetUserId, $command->getUserId());
    }
}
