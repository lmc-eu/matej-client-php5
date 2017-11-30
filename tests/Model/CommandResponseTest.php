<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\ResponseDecodingException;
use Lmc\Matej\TestCase;

class CommandResponseTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideObjectResponses
     * @param \stdClass $objectResponse
     * @param mixed $expectedStatus
     * @param mixed $expectedMessage
     * @param array $expectedData
     */
    public function shouldBeInstantiableFromRawObject(\stdClass $objectResponse, $expectedStatus, $expectedMessage, array $expectedData)
    {
        $commandResponse = CommandResponse::createFromRawCommandResponseObject($objectResponse);
        $this->assertInstanceOf(CommandResponse::class, $commandResponse);
        $this->assertSame($expectedStatus, $commandResponse->getStatus());
        $this->assertSame($expectedMessage, $commandResponse->getMessage());
        $this->assertSame($expectedData, $commandResponse->getData());
    }

    /**
     * @return array[]
     */
    public function provideObjectResponses()
    {
        return ['OK response with only status' => [(object) ['status' => CommandResponse::STATUS_OK], CommandResponse::STATUS_OK, '', []], 'OK response with status and empty message and data' => [(object) ['status' => CommandResponse::STATUS_OK, 'message' => '', 'data' => []], CommandResponse::STATUS_OK, '', []], 'OK response with all fields' => [(object) ['status' => CommandResponse::STATUS_OK, 'message' => 'Nice!', 'data' => [['foo' => 'bar'], ['baz' => 'bak']]], CommandResponse::STATUS_OK, 'Nice!', [['foo' => 'bar'], ['baz' => 'bak']]], 'Invalid error response with status and message' => [(object) ['status' => CommandResponse::STATUS_ERROR, 'message' => 'Internal unhandled error'], CommandResponse::STATUS_ERROR, 'Internal unhandled error', []]];
    }

    /**
     * @test
     * @dataProvider provideResponseStatuses
     * @param mixed $status
     * @param mixed $shouldBeSuccessful
     */
    public function shouldDetectSuccessfulResponse($status, $shouldBeSuccessful)
    {
        $commandResponse = CommandResponse::createFromRawCommandResponseObject((object) ['status' => $status]);
        $this->assertSame($shouldBeSuccessful, $commandResponse->isSuccessful());
    }

    /**
     * @return array[]
     */
    public function provideResponseStatuses()
    {
        return [['status' => CommandResponse::STATUS_OK, 'isSuccessful' => true], ['status' => CommandResponse::STATUS_ERROR, 'isSuccessful' => false], ['status' => CommandResponse::STATUS_INVALID, 'isSuccessful' => false], ['status' => CommandResponse::STATUS_SKIPPED, 'isSuccessful' => false]];
    }

    /** @test */
    public function shouldThrowExceptionIfStatusIsMissing()
    {
        $this->expectException(ResponseDecodingException::class);
        $this->expectExceptionMessage('Status field is missing in command response object');
        CommandResponse::createFromRawCommandResponseObject((object) ['message' => 'Foo', 'data' => [['bar']]]);
    }
}
