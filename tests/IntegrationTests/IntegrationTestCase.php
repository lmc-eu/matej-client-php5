<?php

namespace Lmc\Matej\IntegrationTests;

use Lmc\Matej\Matej;
use Lmc\Matej\Model\Response;
use Lmc\Matej\TestCase;

class IntegrationTestCase extends TestCase
{
    /** @before */
    protected function checkIfConfigured()
    {
        if (!getenv('MATEJ_TEST_ACCOUNTID')) {
            $this->markTestSkipped('Environment variable MATEJ_TEST_ACCOUNTID has to be defined');
        }
        if (!getenv('MATEJ_TEST_APIKEY')) {
            $this->markTestSkipped('Environment variable MATEJ_TEST_APIKEY has to be defined');
        }
    }

    protected function createMatejInstance()
    {
        $instance = new Matej(getenv('MATEJ_TEST_ACCOUNTID'), getenv('MATEJ_TEST_APIKEY'));
        if ($baseUrl = getenv('MATEJ_TEST_BASE_URL')) {
            // intentional assignment
            $instance->setBaseUrl($baseUrl);
        }

        return $instance;
    }

    protected function assertResponseCommandStatuses(Response $response, ...$expectedCommandStatuses)
    {
        $this->assertSame(count($expectedCommandStatuses), $response->getNumberOfCommands());
        $this->assertSame(count(array_intersect($expectedCommandStatuses, ['OK'])), $response->getNumberOfSuccessfulCommands());
        $this->assertSame(count(array_intersect($expectedCommandStatuses, ['ERROR'])), $response->getNumberOfFailedCommands());
        $this->assertSame(count(array_intersect($expectedCommandStatuses, ['SKIPPED'])), $response->getNumberOfSkippedCommands());
        $commandResponses = $response->getCommandResponses();
        foreach ($expectedCommandStatuses as $key => $expectedStatus) {
            $this->assertSame($expectedStatus, $commandResponses[$key]->getStatus());
        }
    }
}
