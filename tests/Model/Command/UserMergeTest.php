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
        $this->assertUserMergeObject($command, $sourceUserId, $targetUserId);
        $command = UserMerge::mergeFromSourceToTargetUser($sourceUserId, $targetUserId);
        $this->assertUserMergeObject($command, $sourceUserId, $targetUserId);
    }

    /**
     * Execute asserts against user merge object
     * @param UserMerge $object
     * @param mixed $sourceUserId
     * @param mixed $targetUserId
     */
    private function assertUserMergeObject($object, $sourceUserId, $targetUserId)
    {
        $this->assertInstanceOf(UserMerge::class, $object);
        $this->assertSame(['type' => 'user-merge', 'parameters' => ['target_user_id' => $targetUserId, 'source_user_id' => $sourceUserId]], $object->jsonSerialize());
    }
}
