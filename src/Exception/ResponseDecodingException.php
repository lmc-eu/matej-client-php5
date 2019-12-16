<?php

namespace Lmc\Matej\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Exception thrown when Matej response cannot be decoded.
 */
final class ResponseDecodingException extends \RuntimeException implements MatejExceptionInterface
{
    public static function forJsonError($jsonErrorMsg, ResponseInterface $response)
    {
        return new self(sprintf("Error decoding Matej response: %s\n\nStatus code: %s %s\nBody:\n%s", $jsonErrorMsg, $response->getStatusCode(), $response->getReasonPhrase(), $response->getBody()));
    }

    public static function forInvalidData(ResponseInterface $response)
    {
        return new self(sprintf("Error decoding Matej response: required data missing.\n\nBody:\n%s", $response->getBody()));
    }

    public static function forInconsistentNumberOfCommands($numberOfCommands, $commandResponsesCount)
    {
        return new self(sprintf('Provided numberOfCommands (%d) is inconsistent with actual count of command responses (%d)', $numberOfCommands, $commandResponsesCount));
    }

    public static function forInconsistentNumbersOfCommandProperties($numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, $numberOfSkippedCommands)
    {
        return new self(sprintf('Provided numberOfCommands (%d) is inconsistent with provided sum of ' . 'numberOfSuccessfulCommands (%d) + numberOfFailedCommands (%d)' . ' + numberOfSkippedCommands (%d)', $numberOfCommands, $numberOfSuccessfulCommands, $numberOfFailedCommands, $numberOfSkippedCommands));
    }
}
