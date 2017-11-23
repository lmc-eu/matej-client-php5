<?php

namespace Lmc\Matej\Http;

use Lmc\Matej\Exception\ResponseDecodingException;
use Lmc\Matej\Model\Response;
use Psr\Http\Message\ResponseInterface;

class ResponseDecoder implements ResponseDecoderInterface
{
    public function decode(ResponseInterface $httpResponse)
    {
        $responseData = json_decode($httpResponse->getBody()->getContents());
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ResponseDecodingException::forJsonError(json_last_error_msg(), $httpResponse);
        }
        if (!$this->isResponseValid($responseData)) {
            throw ResponseDecodingException::forInvalidData($httpResponse);
        }

        return new Response((int) $responseData->commands->number_of_commands, (int) $responseData->commands->number_of_successful_commands, (int) $responseData->commands->number_of_failed_commands, (int) $responseData->commands->number_of_skipped_commands, $responseData->commands->responses);
    }

    private function isResponseValid(\stdClass $responseData)
    {
        return isset($responseData->commands, $responseData->commands->number_of_commands, $responseData->commands->number_of_successful_commands, $responseData->commands->number_of_failed_commands, $responseData->commands->number_of_skipped_commands, $responseData->commands->responses, $responseData->message, $responseData->status);
    }
}
