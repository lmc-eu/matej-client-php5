<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\UnitTestCase;

class UserForgetTest extends UnitTestCase
{
    /** @test */
    public function shouldBeInstantiableViaNamedConstructor()
    {
        $userId = 'user-id';
        $command = UserForget::anonymize($userId);
        $this->assertForgetCommand($command, $userId, UserForget::ANONYMIZE);
        $command = UserForget::delete($userId);
        $this->assertForgetCommand($command, $userId, UserForget::DELETE);
    }

    /**
     * Execute asserts against UserForget command
     *
     * @param UserForget $command
     * @param mixed $userId
     * @param mixed $method
     */
    private function assertForgetCommand($command, $userId, $method)
    {
        $this->assertInstanceOf(UserForget::class, $command);
        $this->assertSame(['type' => 'user-forget', 'parameters' => ['user_id' => $userId, 'method' => $method]], $command->jsonSerialize());
        $this->assertSame($userId, $command->getUserId());
        $this->assertSame($method, $command->getForgetMethod());
    }
}
