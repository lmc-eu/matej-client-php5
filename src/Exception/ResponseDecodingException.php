<?php

namespace Lmc\Matej\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Exception thrown when Matej response cannot be decoded.
 */
class ResponseDecodingException extends AbstractMatejException
{
    public static function forJsonError($jsonErrorMsg, ResponseInterface $response)
    {
        return new self(sprintf("Error decoding Matej response: %s\n\nStatus code: %s %s\nBody:\n%s", $jsonErrorMsg, $response->getStatusCode(), $response->getReasonPhrase(), $response->getBody()));
    }
}
