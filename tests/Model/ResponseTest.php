<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\InvalidDomainModelArgumentException;
use Lmc\Matej\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @dataProvider provideResponseData
     * @test
     * @param mixed $numberOfCommands
     * @param mixed $numberOfSuccessful
     * @param mixed $numberOfFailed
     * @param mixed $numberOfSkipped
     * @param array $commandResponses
     */
    public function shouldBeInstantiable($numberOfCommands, $numberOfSuccessful, $numberOfFailed, $numberOfSkipped, array $commandResponses)
    {
        $response = new Response($numberOfCommands, $numberOfSuccessful, $numberOfFailed, $numberOfSkipped, $commandResponses);
        $this->assertSame($numberOfCommands, $response->getNumberOfCommands());
        $this->assertSame($numberOfSuccessful, $response->getNumberOfSuccessfulCommands());
        $this->assertSame($numberOfFailed, $response->getNumberOfFailedCommands());
        $this->assertSame($numberOfSkipped, $response->getNumberOfSkippedCommands());
        $this->assertContainsOnlyInstancesOf(CommandResponse::class, $response->getCommandResponses());
        $this->assertCount(count($commandResponses), $response->getCommandResponses());
    }

    /**
     * @return array[]
     */
    public function provideResponseData()
    {
        $okCommandResponse = (object) ['status' => CommandResponse::STATUS_OK, 'message' => '', 'data' => []];
        $failedCommandResponse = (object) ['status' => CommandResponse::STATUS_ERROR, 'message' => 'KO', 'data' => []];
        $skippedCommandResponse = (object) ['status' => CommandResponse::STATUS_SKIPPED, 'message' => '', 'data' => []];

        return ['empty response data' => [0, 0, 0, 0, []], 'multiple successful commands' => [2, 2, 0, 0, [$okCommandResponse, $okCommandResponse]], 'multiple failed commands' => [2, 0, 2, 0, [$failedCommandResponse, $failedCommandResponse]], 'multiple skipped commands' => [2, 0, 0, 2, [$skippedCommandResponse, $skippedCommandResponse]], 'multiple successful , failed and skipped commands' => [5, 2, 2, 1, [$failedCommandResponse, $okCommandResponse, $okCommandResponse, $skippedCommandResponse, $failedCommandResponse]]];
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
        $this->expectException(InvalidDomainModelArgumentException::class);
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
