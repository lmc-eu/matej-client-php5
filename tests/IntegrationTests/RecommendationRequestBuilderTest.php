<?php

namespace Lmc\Matej\IntegrationTests;

use Lmc\Matej\Model\Command\Interaction;
use Lmc\Matej\Model\Command\UserMerge;
use Lmc\Matej\Model\Command\UserRecommendation;

/**
 * @covers \Lmc\Matej\RequestBuilder\RecommendationRequestBuilder
 */
class RecommendationRequestBuilderTest extends IntegrationTestCase
{
    /** @test */
    public function shouldExecuteRecommendationRequestOnly()
    {
        $response = $this->createRecommendationRequestBuilder()->send();
        $this->assertResponseCommandStatuses($response, 'SKIPPED', 'SKIPPED', 'OK');
    }

    /** @test */
    public function shouldExecuteRecommendationRequestWithInteraction()
    {
        $response = $this->createRecommendationRequestBuilder()->setInteraction(Interaction::bookmark('integration-test-php-client-user-id-A', 'itemA'))->send();
        $this->assertResponseCommandStatuses($response, 'OK', 'SKIPPED', 'OK');
    }

    /** @test */
    public function shouldExecuteRecommendationRequestWithUserMerge()
    {
        $response = $this->createRecommendationRequestBuilder()->setUserMerge(UserMerge::mergeInto('integration-test-php-client-user-id-A', 'integration-test-php-client-user-id-B'))->send();
        $this->assertResponseCommandStatuses($response, 'SKIPPED', 'OK', 'OK');
    }

    /** @test */
    public function shouldExecuteRecommendationRequestWithUserMergeAndInteraction()
    {
        $response = $this->createRecommendationRequestBuilder()->setUserMerge(UserMerge::mergeInto('integration-test-php-client-user-id-A', 'integration-test-php-client-user-id-B'))->setInteraction(Interaction::bookmark('integration-test-php-client-user-id-A', 'itemA'))->send();
        $this->assertResponseCommandStatuses($response, 'OK', 'OK', 'OK');
    }

    private function createRecommendationRequestBuilder()
    {
        return $this->createMatejInstance()->request()->recommendation(UserRecommendation::create('integration-test-php-client-user-id-A', 5, 'integration-test-scenario', 0.5, 3600));
    }
}
