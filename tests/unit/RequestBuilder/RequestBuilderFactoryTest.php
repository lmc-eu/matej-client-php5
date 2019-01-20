<?php

namespace Lmc\Matej\RequestBuilder;

use Lmc\Matej\Http\RequestManager;
use Lmc\Matej\Model\Command\ItemProperty;
use Lmc\Matej\Model\Command\ItemPropertySetup;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserForget;
use Lmc\Matej\Model\Command\UserRecommendation;
use Lmc\Matej\Model\Request;
use Lmc\Matej\Model\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lmc\Matej\RequestBuilder\RequestBuilderFactory
 */
class RequestBuilderFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideBuilderMethods
     * @param mixed $factoryMethod
     * @param mixed $expectedBuilderClass
     * @param \Closure $minimalBuilderInit
     * @param array $factoryArguments
     */
    public function shouldInstantiateBuilderToBuildAndSendRequest($factoryMethod, $expectedBuilderClass, \Closure $minimalBuilderInit, ...$factoryArguments)
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())->method('sendRequest')->with($this->isInstanceOf(Request::class))->willReturn(new Response(0, 0, 0, 0));
        $factory = new RequestBuilderFactory($requestManagerMock);
        /** @var AbstractRequestBuilder $builder */
        $builder = $factory->{$factoryMethod}(...$factoryArguments);
        // Builders may require some minimal setup to be able to execute the build() method
        $minimalBuilderInit($builder);
        $this->assertInstanceOf($expectedBuilderClass, $builder);
        $this->assertInstanceOf(Request::class, $builder->build());
        // Make sure the builder has been properly configured and it can execute send() via RequestManager mock:
        $this->assertInstanceOf(Response::class, $builder->send());
    }

    /**
     * @return array[]
     */
    public function provideBuilderMethods()
    {
        $itemPropertiesSetupInit = function (ItemPropertiesSetupRequestBuilder $builder) {
            $builder->addProperty(ItemPropertySetup::timestamp('valid_from'));
        };
        $eventInit = function (EventsRequestBuilder $builder) {
            $builder->addItemProperty(ItemProperty::create('item-id', []));
        };
        $campaignInit = function (CampaignRequestBuilder $builder) {
            $builder->addSorting(Sorting::create('item-id', ['item1', 'item2']));
        };
        $forgetInit = function (ForgetRequestBuilder $builder) {
            $builder->addUser(UserForget::anonymize('test-user-for-anonymization'));
        };
        $voidInit = function ($builder) {
        };
        $userRecommendation = UserRecommendation::create('user-id', 1, 'test-scenario', 0.5, 3600);

        return [['getItemProperties', ItemPropertiesGetRequestBuilder::class, $voidInit], ['setupItemProperties', ItemPropertiesSetupRequestBuilder::class, $itemPropertiesSetupInit], ['deleteItemProperties', ItemPropertiesSetupRequestBuilder::class, $itemPropertiesSetupInit], ['events', EventsRequestBuilder::class, $eventInit], ['campaign', CampaignRequestBuilder::class, $campaignInit], ['sorting', SortingRequestBuilder::class, $voidInit, Sorting::create('user-a', ['item-a', 'item-b', 'item-c'])], ['recommendation', RecommendationRequestBuilder::class, $voidInit, $userRecommendation], ['forget', ForgetRequestBuilder::class, $forgetInit], ['resetDatabase', ResetDatabaseRequestBuilder::class, $voidInit]];
    }
}
