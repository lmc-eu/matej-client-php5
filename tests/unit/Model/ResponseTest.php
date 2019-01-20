<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\ResponseDecodingException;
use Lmc\Matej\UnitTestCase;

/**
 * @covers \Lmc\Matej\Exception\ResponseDecodingException
 * @covers \Lmc\Matej\Model\Response
 */
class ResponseTest extends UnitTestCase
{
    /**
     * @dataProvider provideResponseData
     * @test
     * @param mixed $numberOfCommands
     * @param mixed $numberOfSuccessful
     * @param mixed $numberOfFailed
     * @param mixed $numberOfSkipped
     * @param array $commandResponses
     * @param mixed $shouldBeSuccessful
     */
    public function shouldBeInstantiable($numberOfCommands, $numberOfSuccessful, $numberOfFailed, $numberOfSkipped, array $commandResponses, $shouldBeSuccessful)
    {
        $responseId = uniqid((string) time());
        $response = new Response($numberOfCommands, $numberOfSuccessful, $numberOfFailed, $numberOfSkipped, $commandResponses, $responseId);
        $this->assertSame($numberOfCommands, $response->getNumberOfCommands());
        $this->assertSame($numberOfSuccessful, $response->getNumberOfSuccessfulCommands());
        $this->assertSame($numberOfFailed, $response->getNumberOfFailedCommands());
        $this->assertSame($numberOfSkipped, $response->getNumberOfSkippedCommands());
        $this->assertSame($responseId, $response->getResponseId());
        $this->assertSame($shouldBeSuccessful, $response->isSuccessful());
        $this->assertCount(count($commandResponses), $response->getCommandResponses());
        $this->assertContainsOnlyInstancesOf(CommandResponse::class, $response->getCommandResponses());
        for ($i = 0; $i < count($commandResponses); $i++) {
            $this->assertInstanceOf(CommandResponse::class, $response->getCommandResponse($i));
        }
    }

    /**
     * @return array[]
     */
    public function provideResponseData()
    {
        $okCommandResponse = (object) ['status' => CommandResponse::STATUS_OK, 'message' => '', 'data' => []];
        $failedCommandResponse = (object) ['status' => CommandResponse::STATUS_INVALID, 'message' => 'KO', 'data' => []];
        $skippedCommandResponse = (object) ['status' => CommandResponse::STATUS_SKIPPED, 'message' => '', 'data' => []];

        return ['empty response data' => [0, 0, 0, 0, [], true], 'multiple ok commands' => [2, 2, 0, 0, [$okCommandResponse, $okCommandResponse], true], 'multiple failed commands' => [2, 0, 2, 0, [$failedCommandResponse, $failedCommandResponse], false], 'multiple skipped commands' => [2, 0, 0, 2, [$skippedCommandResponse, $skippedCommandResponse], true], 'ok and skipped command' => [2, 1, 0, 1, [$okCommandResponse, $skippedCommandResponse], true], 'ok and failed commands' => [2, 1, 1, 0, [$okCommandResponse, $failedCommandResponse], false], 'skipped and failed command' => [2, 0, 1, 1, [$skippedCommandResponse, $skippedCommandResponse], false], 'multiple successful , failed and skipped commands' => [5, 2, 2, 1, [$failedCommandResponse, $okCommandResponse, $okCommandResponse, $skippedCommandResponse, $failedCommandResponse], false]];
    }

    /**
     * @dataProvider provideInconsistentData
     * @test
     * @param mixed $numberOfCommands
     * @param mixed $numberOfSuccessful
     * @param mixed $numberOfFailed
     * @param mixed $numberOfSkipped
     * @param array $commandResponses
     * @param mixed $expectedExceptionMessage
     */
    public function shouldThrowExceptionWhenInconsistentDataProvided($numberOfCommands, $numberOfSuccessful, $numberOfFailed, $numberOfSkipped, array $commandResponses, $expectedExceptionMessage)
    {
        $this->expectException(ResponseDecodingException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        new Response($numberOfCommands, $numberOfSuccessful, $numberOfFailed, $numberOfSkipped, $commandResponses);
    }

    /**
     * @return array[]
     */
    public function provideInconsistentData()
    {
        $commandResponse = (object) ['status' => CommandResponse::STATUS_OK, 'message' => '', 'data' => []];

        return ['numberOfCommands is more than command responses count' => [5, 0, 0, 0, [], 'Provided numberOfCommands (5) is inconsistent with actual count of command responses (0)'], 'numberOfCommands is less than command responses count' => [0, 0, 0, 0, [$commandResponse, $commandResponse], 'Provided numberOfCommands (0) is inconsistent with actual count of command responses (2)'], 'numberOfCommands does not match sum of successful and failed numbers' => [2, 2, 1, 1, [$commandResponse, $commandResponse], 'Provided numberOfCommands (2) is inconsistent with provided sum of numberOfSuccessfulCommands (2)' . ' + numberOfFailedCommands (1) + numberOfSkippedCommands (1)']];
    }
}
