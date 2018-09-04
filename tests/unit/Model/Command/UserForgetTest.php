<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Command\Constants\UserForgetMethod;
use Lmc\Matej\UnitTestCase;

class UserForgetTest extends UnitTestCase
{
    /** @test */
    public function shouldBeInstantiableViaNamedConstructor()
    {
        $userId = 'user-id';
        $command = UserForget::anonymize($userId);
        $this->assertForgetCommand($command, $userId, UserForgetMethod::ANONYMIZE());
        $command = UserForget::delete($userId);
        $this->assertForgetCommand($command, $userId, UserForgetMethod::DELETE());
    }

    /**
     * Execute asserts against UserForget command
     *
     * @param UserForget $command
     * @param mixed $userId
     * @param UserForgetMethod $method
     */
    private function assertForgetCommand($command, $userId, UserForgetMethod $method)
    {
        $this->assertInstanceOf(UserForget::class, $command);
        $this->assertSame(['type' => 'user-forget', 'parameters' => ['user_id' => $userId, 'method' => $method->jsonSerialize()]], $command->jsonSerialize());
        $this->assertSame($userId, $command->getUserId());
        $this->assertEquals($method, $command->getForgetMethod());
    }
}
