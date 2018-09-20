<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Assertion;
use Lmc\Matej\Model\Command\Constants\MinimalRelevance;

/**
 * Deliver personalized recommendations for the given user.
 */
class UserRecommendation extends AbstractCommand implements UserAwareInterface
{
    const FILTER_TYPE_MQL = 'mql';
    /** @var string */
    protected $filterOperator = 'and';
    /** @var string */
    private $userId;
    /** @var int */
    private $count;
    /** @var string */
    private $scenario;
    /** @var float */
    private $rotationRate;
    /** @var int */
    private $rotationTime;
    /** @var bool */
    private $hardRotation = false;
    /** @var MinimalRelevance */
    private $minimalRelevance;
    /** @var string[] */
    private $filters = [];
    /** @var string */
    private $filterType = self::FILTER_TYPE_MQL;
    /** @var string|null */
    private $modelName;
    /** @var string[] */
    private $responseProperties = [];
    /** @var bool */
    private $allowSeen = false;

    private function __construct($userId, $count, $scenario, $rotationRate, $rotationTime, array $responseProperties)
    {
        $this->minimalRelevance = MinimalRelevance::LOW();
        $this->setUserId($userId);
        $this->setCount($count);
        $this->setScenario($scenario);
        $this->setRotationRate($rotationRate);
        $this->setRotationTime($rotationTime);
        $this->setResponseProperties($responseProperties);
    }

    /**
     * @param string $userId
     * @param int $count Number of requested recommendations. The real number of recommended items could be lower or
     * even zero when there are no items relevant for the user.
     * @param string $scenario Name of the place where recommendations are applied - eg. 'search-results-page',
     * 'emailing', 'empty-search-results, 'homepage', ...
     * @param float $rotationRate How much should the item be penalized for being recommended again in the near future.
     * Set from 0.0 for no rotation (same items will be recommended) up to 1.0 (same items should not be recommended).
     * @param int $rotationTime Specify for how long will the item's rotationRate be taken in account and so the item
     * is penalized for recommendations.
     * @param string[] $responseProperties Specify which properties you want to retrieve from Matej alongside the item_id.
     * @return static
     */
    public static function create($userId, $count, $scenario, $rotationRate, $rotationTime, array $responseProperties = [])
    {
        return new static($userId, $count, $scenario, $rotationRate, $rotationTime, $responseProperties);
    }

    /**
     * Even with rotation rate 1.0 user could still obtain the same recommendations in some edge cases.
     * To prevent this, enable hard rotation - recommended items are then excluded until rotation time is expired.
     * By default hard rotation is not enabled.
     *
     * @return $this
     */
    public function enableHardRotation()
    {
        $this->hardRotation = true;

        return $this;
    }

    /**
     * Define threshold of how much relevant must the recommended items be to be returned.
     * Default minimal relevance is "low".
     *
     * @param MinimalRelevance $minimalRelevance
     * @return $this
     */
    public function setMinimalRelevance(MinimalRelevance $minimalRelevance)
    {
        $this->minimalRelevance = $minimalRelevance;

        return $this;
    }

    /**
     * Add a filter to already added filters (including the default filter).
     *
     * @param mixed $filter
     * @return $this
     */
    public function addFilter($filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Overwrite all filters by custom one. Note this will override also the default filter.
     *
     * @param array $filters
     * @return $this
     */
    public function setFilters(array $filters)
    {
        Assertion::allString($filters);
        $this->filters = $filters;

        return $this;
    }

    /**
     * Add another response property you want returned. item_id is always returned by Matej.
     * @param mixed $property
     */
    public function addResponseProperty($property)
    {
        Assertion::typeIdentifier($property);
        $this->responseProperties[] = $property;

        return $this;
    }

    /**
     * Set all response properties you want returned. item_id is always returned by Matej, even when you don't specify it.
     *
     * @param string[] $properties
     * @return $this
     */
    public function setResponseProperties(array $properties)
    {
        Assertion::allTypeIdentifier($properties);
        $this->responseProperties = $properties;

        return $this;
    }

    /***
     * Set A/B model name
     *
     * @return $this
     */
    public function setModelName($modelName)
    {
        Assertion::typeIdentifier($modelName);
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * Allow items, that the user has already "seen"
     *
     * By default user won't see any items, that it has visitted (and we have recorded DetailView interaction.)
     * If you want to circumvent this, and get recommendations including the ones, that the user has already visitted,
     * you can set the "seen" allowance here.
     * @param mixed $seen
     */
    public function setAllowSeen($seen)
    {
        $this->allowSeen = $seen;

        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    protected function setUserId($userId)
    {
        Assertion::typeIdentifier($userId);
        $this->userId = $userId;
    }

    /**
     * @param int $count
     */
    protected function setCount($count)
    {
        Assertion::integer($count);
        Assertion::greaterThan($count, 0);
        $this->count = $count;
    }

    protected function setScenario($scenario)
    {
        Assertion::typeIdentifier($scenario);
        $this->scenario = $scenario;
    }

    /**
     * @param float $rotationRate
     */
    protected function setRotationRate($rotationRate)
    {
        Assertion::float($rotationRate);
        Assertion::between($rotationRate, 0, 1);
        $this->rotationRate = $rotationRate;
    }

    /**
     * @param int $rotationTime
     */
    protected function setRotationTime($rotationTime)
    {
        Assertion::integer($rotationTime);
        Assertion::greaterOrEqualThan($rotationTime, 0);
        $this->rotationTime = $rotationTime;
    }

    protected function assembleFiltersString()
    {
        return implode(' ' . $this->filterOperator . ' ', $this->filters);
    }

    protected function getCommandType()
    {
        return 'user-based-recommendations';
    }

    protected function getCommandParameters()
    {
        $parameters = ['user_id' => $this->userId, 'count' => $this->count, 'scenario' => $this->scenario, 'rotation_rate' => $this->rotationRate, 'rotation_time' => $this->rotationTime, 'hard_rotation' => $this->hardRotation, 'min_relevance' => $this->minimalRelevance->jsonSerialize(), 'filter' => $this->assembleFiltersString(), 'filter_type' => $this->filterType, 'properties' => $this->responseProperties];
        if ($this->modelName !== null) {
            $parameters['model_name'] = $this->modelName;
        }
        if ($this->allowSeen !== false) {
            $parameters['allow_seen'] = $this->allowSeen;
        }

        return $parameters;
    }
}
