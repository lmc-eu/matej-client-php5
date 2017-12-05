<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\ResponseDecodingException;

class Response
{
    /** @var CommandResponse[] */
    private $commandResponses = [];
    /** @var int */
    private $numberOfCommands;
    /** @var int */
    private $numberOfSuccessfulCommands;
    /** @var int */
    private $numberOfFailedCommands;
    /** @var int */
    private $numberOfSkippedCommands;
    /** @var string|null */
    private $responseId;

    public function __construct($numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, $numberOfSkippedCommands, array $commandResponses = [], $responseId = null)
    {
        $this->numberOfCommands = $numberOfCommands;
        $this->numberOfSuccessfulCommands = $numberOfSuccessfulCommands;
        $this->numberOfFailedCommands = $numberOfFailedCommands;
        $this->numberOfSkippedCommands = $numberOfSkippedCommands;
        $this->responseId = $responseId;
        foreach ($commandResponses as $rawCommandResponse) {
            $this->commandResponses[] = CommandResponse::createFromRawCommandResponseObject($rawCommandResponse);
        }
        if ($this->numberOfCommands !== count($commandResponses)) {
            throw ResponseDecodingException::forInconsistentNumberOfCommands($this->numberOfCommands, count($commandResponses));
        }
        $commandSum = $this->numberOfSuccessfulCommands + $this->numberOfFailedCommands + $this->numberOfSkippedCommands;
        if ($this->numberOfCommands !== $commandSum) {
            throw ResponseDecodingException::forInconsistentNumbersOfCommandProperties($this->numberOfCommands, $this->numberOfSuccessfulCommands, $this->numberOfFailedCommands, $this->numberOfSkippedCommands);
        }
        $this->responseId = $responseId;
    }

    public function getNumberOfCommands()
    {
        return $this->numberOfCommands;
    }

    public function getNumberOfSuccessfulCommands()
    {
        return $this->numberOfSuccessfulCommands;
    }

    public function getNumberOfFailedCommands()
    {
        return $this->numberOfFailedCommands;
    }

    public function getNumberOfSkippedCommands()
    {
        return $this->numberOfSkippedCommands;
    }

    /**
     * Each Command which was part of request batch has here corresponding CommandResponse - on the same index on which
     * the Command was added to the request batch.
     *
     * @return CommandResponse[]
     */
    public function getCommandResponses()
    {
        return $this->commandResponses;
    }

    public function getResponseId()
    {
        return $this->responseId;
    }
}
