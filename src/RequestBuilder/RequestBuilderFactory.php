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

    public function getItemProperties()
    {
        $requestBuilder = new ItemPropertiesGetRequestBuilder();
        $this->setupBuilder($requestBuilder);

        return $requestBuilder;
    }

    /**
     * Define new properties into the database. Those properties will be created and subsequently accepted by Matej.
     *
     * @return ItemPropertiesSetupRequestBuilder
     */
    public function setupItemProperties()
    {
        $requestBuilder = new ItemPropertiesSetupRequestBuilder();
        $this->setupBuilder($requestBuilder);

        return $requestBuilder;
    }

    /**
     * Added item properties will be IRREVERSIBLY removed from all items in the database and the item property will
     * from now be rejected by Matej.
     *
     * @return ItemPropertiesSetupRequestBuilder
     */
    public function deleteItemProperties()
    {
        $requestBuilder = new ItemPropertiesSetupRequestBuilder($shouldDelete = true);
        $this->setupBuilder($requestBuilder);

        return $requestBuilder;
    }

    /**
     * @return EventsRequestBuilder
     */
    public function events()
    {
        $requestBuilder = new EventsRequestBuilder();
        $this->setupBuilder($requestBuilder);

        return $requestBuilder;
    }

    /**
     * @return CampaignRequestBuilder
     */
    public function campaign()
    {
        $requestBuilder = new CampaignRequestBuilder();
        $this->setupBuilder($requestBuilder);

        return $requestBuilder;
    }

    /**
     * @param Sorting $sorting
     * @return SortingRequestBuilder
     */
    public function sorting(Sorting $sorting)
    {
        $requestBuilder = new SortingRequestBuilder($sorting);
        $this->setupBuilder($requestBuilder);

        return $requestBuilder;
    }

    /**
     * @param UserRecommendation $recommendation
     * @return RecommendationRequestBuilder
     */
    public function recommendation(UserRecommendation $recommendation)
    {
        $requestBuilder = new RecommendationRequestBuilder($recommendation);
        $this->setupBuilder($requestBuilder);

        return $requestBuilder;
    }

    /**
     * @return ForgetRequestBuilder
     */
    public function forget()
    {
        $requestBuilder = new ForgetRequestBuilder();
        $this->setupBuilder($requestBuilder);

        return $requestBuilder;
    }

    /**
     * @return ResetDatabaseRequestBuilder
     */
    public function resetDatabase()
    {
        $requestBuilder = new ResetDatabaseRequestBuilder();
        $this->setupBuilder($requestBuilder);

        return $requestBuilder;
    }

    private function setupBuilder(AbstractRequestBuilder $requestBuilder)
    {
        $requestBuilder->setRequestManager($this->requestManager);
    }
}
