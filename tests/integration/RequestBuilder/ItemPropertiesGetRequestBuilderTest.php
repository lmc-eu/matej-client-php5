<?php

namespace Lmc\Matej\IntegrationTests\RequestBuilder;

use Lmc\Matej\IntegrationTests\IntegrationTestCase;
use Lmc\Matej\Model\Response\ItemPropertiesListResponse;

/**
 * @covers \Lmc\Matej\Model\Response\ItemPropertiesListResponse
 * @covers \Lmc\Matej\RequestBuilder\ItemPropertiesGetRequestBuilder
 */
class ItemPropertiesGetRequestBuilderTest extends IntegrationTestCase
{
    /** @test */
    public function shouldGetListOfPropertiesFromMatej()
    {
        $response = $this->createMatejInstance()->request()->getItemProperties()->send();
        $this->assertResponseCommandStatuses($response, 'OK');
        $this->assertInstanceOf(ItemPropertiesListResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('', $response->getMessage());
        $this->assertSame('OK', $response->getStatus());
        $this->assertInternalType('array', $response->getData());
    }
}
