<?php

namespace Lmc\Matej\RequestBuilder;

use Lmc\Matej\Http\RequestManager;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserRecommendation;

/**
 * Factory to create concrete RequestBuilder which helps you to create request for each Matej API
 */
class RequestBuilderFactory
{
    /** @var RequestManager */
    private $requestManager;

    public function __construct(RequestManager $requestManager)
    {
        $this->requestManager = $requestManager;
    }

    /**
     * Define new properties into the database. Those properties will be created and subsequently accepted by Matej.
     *
     * @return ItemPropertiesSetupRequestBuilder
     */
    public function setupItemProperties()
    {
        return $this->createConfiguredBuilder(ItemPropertiesSetupRequestBuilder::class);
    }

    /**
     * Added item properties will be IRREVERSIBLY removed from all items in the database and the item property will
     * from now be rejected by Matej.
     *
     * @return ItemPropertiesSetupRequestBuilder
     */
    public function deleteItemProperties()
    {
        return $this->createConfiguredBuilder(ItemPropertiesSetupRequestBuilder::class, $shouldDelete = true);
    }

    /**
     * @return EventsRequestBuilder
     */
    public function events()
    {
        return $this->createConfiguredBuilder(EventsRequestBuilder::class);
    }

    /**
     * @return CampaignRequestBuilder
     */
    public function campaign()
    {
        return $this->createConfiguredBuilder(CampaignRequestBuilder::class);
    }

    /**
     * @param Sorting $sorting
     * @return SortingRequestBuilder
     */
    public function sorting(Sorting $sorting)
    {
        return $this->createConfiguredBuilder(SortingRequestBuilder::class, $sorting);
    }

    /**
     * @param UserRecommendation $recommendation
     * @return RecommendationRequestBuilder
     */
    public function recommendation(UserRecommendation $recommendation)
    {
        return $this->createConfiguredBuilder(RecommendationRequestBuilder::class, $recommendation);
    }

    /**
     * @param string $builderClass
     * @param array ...$args
     * @return mixed
     */
    private function createConfiguredBuilder($builderClass, ...$args)
    {
        /** @var AbstractRequestBuilder $requestBuilder */
        $requestBuilder = new $builderClass(...$args);
        $requestBuilder->setRequestManager($this->requestManager);

        return $requestBuilder;
    }
}
