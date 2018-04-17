<?php

namespace Lmc\Matej\IntegrationTests;

use Lmc\Matej\Model\Command\Sorting;

class MatejTest extends IntegrationTestCase
{
    /** @test */
    public function shouldReceiveRequestIdInResponse()
    {
        $requestId = uniqid('integration-test-php-client-request-id');
        $response = static::createMatejInstance()->request()->sorting(Sorting::create('integration-test-php-client-user-id-A', ['itemA', 'itemB']))->setRequestId($requestId)->send();
        $this->assertSame($requestId, $response->getResponseId());
    }
}
