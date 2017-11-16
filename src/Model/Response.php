<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\InvalidDomainModelArgumentException;

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

    public function __construct($numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, array $commandResponses = [])
    {
        $this->numberOfCommands = $numberOfCommands;
        $this->numberOfSuccessfulCommands = $numberOfSuccessfulCommands;
        $this->numberOfFailedCommands = $numberOfFailedCommands;
        foreach ($commandResponses as $rawCommandResponse) {
            $this->commandResponses[] = CommandResponse::createFromRawCommandResponseObject($rawCommandResponse);
        }
        if ($this->numberOfCommands !== count($commandResponses)) {
            throw InvalidDomainModelArgumentException::forInconsistentNumberOfCommands($this->numberOfCommands, count($commandResponses));
        }
        if ($this->numberOfCommands !== $this->numberOfSuccessfulCommands + $this->numberOfFailedCommands) {
            throw InvalidDomainModelArgumentException::forInconsistentNumbersOfCommandProperties($this->numberOfCommands, $this->numberOfSuccessfulCommands, $this->numberOfFailedCommands);
        }
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
}
