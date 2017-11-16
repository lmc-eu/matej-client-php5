<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\InvalidDomainModelArgumentException;
use PHPUnit\Framework\TestCase;

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
        return ['OK response with only status' => [(object) ['status' => 'OK'], 'OK', '', []], 'OK response with status and empty message and data' => [(object) ['status' => 'OK', 'message' => '', 'data' => []], 'OK', '', []], 'OK response with all fields' => [(object) ['status' => 'OK', 'message' => 'Nice!', 'data' => [['foo' => 'bar'], ['baz' => 'bak']]], 'OK', 'Nice!', [['foo' => 'bar'], ['baz' => 'bak']]], 'Error response with status and message' => [(object) ['status' => 'ERROR', 'message' => 'DuplicateKeyError(Duplicate key error collection)'], 'ERROR', 'DuplicateKeyError(Duplicate key error collection)', []]];
    }

    /** @test */
    public function shouldThrowExceptionIfStatusIsMissing()
    {
        $this->expectException(InvalidDomainModelArgumentException::class);
        $this->expectExceptionMessage('Status field is missing in command response object');
        CommandResponse::createFromRawCommandResponseObject((object) ['message' => 'Foo', 'data' => [['bar']]]);
    }
}
