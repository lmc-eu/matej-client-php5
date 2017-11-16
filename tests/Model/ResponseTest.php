<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\InvalidDomainModelArgumentException;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @dataProvider provideResponseData
     * @test
     * @param mixed $numberOfCommands
     * @param mixed $numberOfSuccessfulCommands
     * @param mixed $numberOfFailedCommands
     * @param array $commandResponses
     */
    public function shouldBeInstantiable($numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, array $commandResponses)
    {
        $response = new Response($numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, $commandResponses);
        $this->assertSame($numberOfCommands, $response->getNumberOfCommands());
        $this->assertSame($numberOfSuccessfulCommands, $response->getNumberOfSuccessfulCommands());
        $this->assertSame($numberOfFailedCommands, $response->getNumberOfFailedCommands());
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

        return ['empty response data' => [0, 0, 0, []], 'multiple successful commands' => [2, 2, 0, [$okCommandResponse, $okCommandResponse]], 'multiple failed commands' => [2, 0, 2, [$failedCommandResponse, $failedCommandResponse]], 'multiple failed and successful commands' => [4, 2, 2, [$failedCommandResponse, $okCommandResponse, $okCommandResponse, $failedCommandResponse]]];
    }

    /**
     * @dataProvider provideInconsistentData
     * @test
     * @param mixed $numberOfCommands
     * @param mixed $numberOfSuccessfulCommands
     * @param mixed $numberOfFailedCommands
     * @param array $commandResponses
     * @param mixed $expectedExceptionMessage
     */
    public function shouldThrowExceptionWhenInconsistentDataProvided($numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, array $commandResponses, $expectedExceptionMessage)
    {
        $this->expectException(InvalidDomainModelArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        new Response($numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, $commandResponses);
    }

    /**
     * @return array[]
     */
    public function provideInconsistentData()
    {
        $commandResponse = (object) ['status' => CommandResponse::STATUS_OK, 'message' => '', 'data' => []];

        return ['numberOfCommands is more than command responses count' => [5, 0, 0, [], 'Provided numberOfCommands (5) is inconsistent with actual count of command responses (0)'], 'numberOfCommands is less than command responses count' => [0, 0, 0, [$commandResponse, $commandResponse], 'Provided numberOfCommands (0) is inconsistent with actual count of command responses (2)'], 'numberOfCommands does not match sum of successful and failed numbers' => [2, 2, 1, [$commandResponse, $commandResponse], 'Provided numberOfCommands (2) is inconsistent with provided sum of numberOfSuccessfulCommands (2)' . ' and numberOfFailedCommands (1)']];
    }
}
