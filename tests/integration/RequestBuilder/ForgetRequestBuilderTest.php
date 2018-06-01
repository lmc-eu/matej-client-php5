<?php

namespace Lmc\Matej\IntegrationTests\RequestBuilder;

use Lmc\Matej\IntegrationTests\IntegrationTestCase;
use Lmc\Matej\Model\Command\UserForget;

/**
 * @covers \Lmc\Matej\RequestBuilder\ForgetRequestBuilder
 */
class ForgetRequestBuilderTest extends IntegrationTestCase
{
    /** @test */
    public function shouldExecuteForgetRequest()
    {
        $response = static::createMatejInstance()->request()->forget()->addUser(UserForget::delete('user-a'))->addUser(UserForget::anonymize('user-b'))->addUsers([UserForget::delete('user-c'), UserForget::anonymize('user-d')])->send();
        $this->assertResponseCommandStatuses($response, ...$this->generateOkStatuses(4));
    }
}
