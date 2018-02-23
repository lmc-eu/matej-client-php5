<?php

namespace Lmc\Matej\Model\Response;

use Lmc\Matej\Model\CommandResponse;
use Lmc\Matej\UnitTestCase;

class PlainResponseTest extends UnitTestCase
{
    /** @test */
    public function shouldBeInstantiable()
    {
        $commandResponse = (object) ['status' => CommandResponse::STATUS_OK, 'message' => 'MOCK_MESSAGE', 'data' => ['MOCK' => 'DATA']];
        $response = new PlainResponse(1, 1, 0, 0, [$commandResponse]);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame(CommandResponse::STATUS_OK, $response->getStatus());
        $this->assertSame('MOCK_MESSAGE', $response->getMessage());
        $this->assertSame(['MOCK' => 'DATA'], $response->getData());
    }
}
