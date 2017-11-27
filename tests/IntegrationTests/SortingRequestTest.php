<?php

namespace Lmc\Matej\IntegrationTests;

use Lmc\Matej\Model\Command\Interaction;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserMerge;

/**
 * @covers \Lmc\Matej\RequestBuilder\SortingRequestBuilder
 */
class SortingRequestTest extends IntegrationTestCase
{
    /** @test */
    public function shouldExecuteSortingRequestOnly()
    {
        $response = $this->createMatejInstance()->request()->sorting(Sorting::create('integration-test-php-client-user-id-A', ['itemA', 'itemB', 'itemC']))->send();
        $this->assertResponseCommandStatuses($response, 'SKIPPED', 'SKIPPED', 'OK');
    }

    /** @test */
    public function shouldFailOnTooLittleItemIds()
    {
        $response = $this->createMatejInstance()->request()->sorting(Sorting::create('integration-test-php-client-user-id-A', ['itemA']))->send();
        $this->assertResponseCommandStatuses($response, 'SKIPPED', 'SKIPPED', 'OK');
    }

    /** @test */
    public function shouldExecuteSortingRequestWithInteraction()
    {
        $response = $this->createMatejInstance()->request()->sorting(Sorting::create('integration-test-php-client-user-id-A', ['itemA', 'itemB', 'itemC']))->setInteraction(Interaction::bookmark('integration-test-php-client-user-id-A', 'itemA'))->send();
        $this->assertResponseCommandStatuses($response, 'OK', 'SKIPPED', 'OK');
    }

    /** @test */
    public function shouldExecuteSortingRequestWithUserMerge()
    {
        $response = $this->createMatejInstance()->request()->sorting(Sorting::create('integration-test-php-client-user-id-A', ['itemA', 'itemB', 'itemC']))->setUserMerge(UserMerge::mergeInto('integration-test-php-client-user-id-A', 'integration-test-php-client-user-id-B'))->send();
        $this->assertResponseCommandStatuses($response, 'SKIPPED', 'OK', 'OK');
    }

    /** @test */
    public function shouldExecuteSortingRequestWithUserMergeAndInteraction()
    {
        $response = $this->createMatejInstance()->request()->sorting(Sorting::create('integration-test-php-client-user-id-A', ['itemA', 'itemB', 'itemC']))->setUserMerge(UserMerge::mergeInto('integration-test-php-client-user-id-A', 'integration-test-php-client-user-id-B'))->setInteraction(Interaction::bookmark('integration-test-php-client-user-id-A', 'itemA'))->send();
        $this->assertResponseCommandStatuses($response, 'OK', 'OK', 'OK');
    }
}
