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
    private $hardRotation;
    /** @var MinimalRelevance */
    private $minimalRelevance;
    /** @var string[] */
    private $filters;
    /** @var string */
    private $filterType = self::FILTER_TYPE_MQL;
    /** @var string|null */
    private $modelName;
    /** @var string[] */
    private $responseProperties;
    /** @var bool */
    private $allowSeen = false;
    /** @var Boost[] */
    private $boosts = [];

    private function __construct($userId, $scenario)
    {
        $this->setUserId($userId);
        $this->setScenario($scenario);
    }

    /**
     * @param string $userId
     * @param string $scenario Name of the place where recommendations are applied - eg. 'search-results-page',
     * 'emailing', 'empty-search-results, 'homepage', ...
     * @return static
     */
    public static function create($userId, $scenario)
    {
        return new static($userId, $scenario);
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
        if ($this->filters == null) {
            $this->filters = [];
        }
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
     *
     * @param string $property
     * @return $this
     */
    public function addResponseProperty($property)
    {
        Assertion::typeIdentifier($property);
        if ($this->responseProperties == null) {
            $this->responseProperties = [];
        }
        $this->responseProperties[] = $property;

        return $this;
    }

    /**
     * Set all response properties you want returned. item_id is always returned by Matej, even when you don't specify
     * it.
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
     * By default user won't see any items, that it has visited (and we have recorded DetailView  interaction.)
     * If you want to circumvent this, and get recommendations including the ones, that the user has already visited,
     * you can set the "seen" allowance here.
     * @param bool $seen
     */
    public function setAllowSeen($seen)
    {
        Assertion::boolean($seen);
        $this->allowSeen = $seen;

        return $this;
    }

    /**
     * Add a boost rule to already added rules.
     *
     * @param Boost $boost
     * @return $this
     */
    public function addBoost(Boost $boost)
    {
        $this->boosts[] = $boost;

        return $this;
    }

    /**
     * Set boosts. Removes all previously set rules.
     *
     * @param array $boosts
     * @return $this
     */
    public function setBoosts(array $boosts)
    {
        $this->boosts = $boosts;

        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set number of requested recommendations. The real number of recommended items could be lower or even zero when
     * there are no items relevant for the user.
     *
     * @param int $count
     * @return $this
     */
    public function setCount($count)
    {
        Assertion::integer($count);
        Assertion::greaterThan($count, 0);
        $this->count = $count;

        return $this;
    }

    /**
     * Set how much should the item be penalized for being recommended again in the near future.
     *
     * @param float $rotationRate
     * @return $this
     */
    public function setRotationRate($rotationRate)
    {
        Assertion::float($rotationRate);
        Assertion::between($rotationRate, 0, 1);
        $this->rotationRate = $rotationRate;

        return $this;
    }

    /**
     * Specify for how long will the item's rotationRate be taken in account and so the item is penalized for
     * recommendations.
     *
     * @param int $rotationTime
     * @return $this
     */
    public function setRotationTime($rotationTime)
    {
        Assertion::integer($rotationTime);
        Assertion::greaterOrEqualThan($rotationTime, 0);
        $this->rotationTime = $rotationTime;

        return $this;
    }

    protected function setUserId($userId)
    {
        Assertion::typeIdentifier($userId);
        $this->userId = $userId;
    }

    protected function setScenario($scenario)
    {
        Assertion::typeIdentifier($scenario);
        $this->scenario = $scenario;
    }

    protected function assembleFiltersString()
    {
        return implode(' ' . $this->filterOperator . ' ', $this->filters);
    }

    protected function getCommandType()
    {
        return 'user-based-recommendations';
    }

    protected function getSerializedBoosts()
    {
        return array_map(function (Boost $boost) {
            return $boost->jsonSerialize();
        }, $this->boosts);
    }

    protected function getCommandParameters()
    {
        $parameters = ['user_id' => $this->userId, 'scenario' => $this->scenario];
        if ($this->count !== null) {
            $parameters['count'] = $this->count;
        }
        if ($this->rotationRate !== null) {
            $parameters['rotation_rate'] = $this->rotationRate;
        }
        if ($this->rotationRate !== null) {
            $parameters['rotation_time'] = $this->rotationTime;
        }
        if ($this->modelName !== null) {
            $parameters['model_name'] = $this->modelName;
        }
        if ($this->allowSeen !== false) {
            $parameters['allow_seen'] = $this->allowSeen;
        }
        if (!empty($this->boosts)) {
            $parameters['boost_rules'] = $this->getSerializedBoosts();
        }
        if ($this->hardRotation !== null) {
            $parameters['hard_rotation'] = $this->hardRotation;
        }
        if ($this->hardRotation !== null) {
            $parameters['hard_rotation'] = $this->hardRotation;
        }
        if ($this->minimalRelevance !== null) {
            $parameters['min_relevance'] = $this->minimalRelevance->jsonSerialize();
        }
        if ($this->filters !== null) {
            $parameters['filter'] = $this->assembleFiltersString();
            $parameters['filter_type'] = $this->filterType;
        }
        if ($this->responseProperties !== null) {
            $parameters['properties'] = $this->responseProperties;
        }

        return $parameters;
    }
}
