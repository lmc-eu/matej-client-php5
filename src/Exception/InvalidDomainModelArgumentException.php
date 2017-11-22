<?php

namespace Lmc\Matej\Exception;

/**
 * Exception thrown when invalid argument is passed while creating domain model.
 */
class InvalidDomainModelArgumentException extends LogicException
{
    public static function forInconsistentNumberOfCommands($numberOfCommands, $commandResponsesCount)
    {
        return new self(sprintf('Provided numberOfCommands (%d) is inconsistent with actual count of command responses (%d)', $numberOfCommands, $commandResponsesCount));
    }

    public static function forInconsistentNumbersOfCommandProperties($numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, $numberOfSkippedCommands)
    {
        return new self(sprintf('Provided numberOfCommands (%d) is inconsistent with provided sum of ' . 'numberOfSuccessfulCommands (%d) + numberOfFailedCommands (%d)' . ' + numberOfSkippedCommands (%d)', $numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, $numberOfSkippedCommands));
    }
}
