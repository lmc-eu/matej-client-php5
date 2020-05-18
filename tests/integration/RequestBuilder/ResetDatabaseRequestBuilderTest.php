<?php

namespace Lmc\Matej\IntegrationTests\RequestBuilder;

use Lmc\Matej\IntegrationTests\IntegrationTestCase;
use Lmc\Matej\Model\Response\PlainResponse;

/**
 * @covers \Lmc\Matej\Model\Response\PlainResponse
 * @covers \Lmc\Matej\RequestBuilder\ResetDatabaseRequestBuilder
 */
class ResetDatabaseRequestBuilderTest extends IntegrationTestCase
{
    /** @test */
    public function shouldResetMatejDatabase()
    {
        $response = static::createMatejInstance()->request()->resetDatabase()->send();
        $this->assertResponseCommandStatuses($response, 'OK');
        $this->assertInstanceOf(PlainResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('', $response->getMessage());
        $this->assertSame('OK', $response->getStatus());
        $this->assertIsArray($response->getData());
    }
}
