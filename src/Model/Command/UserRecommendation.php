<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Assertion;

/**
 * Deliver personalized recommendations for the given user.
 */
class UserRecommendation extends AbstractCommand implements UserAwareInterface
{
    const MINIMAL_RELEVANCE_LOW = 'low';
    const MINIMAL_RELEVANCE_MEDIUM = 'medium';
    const MINIMAL_RELEVANCE_HIGH = 'high';
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
    /** @var string */
    private $minimalRelevance = self::MINIMAL_RELEVANCE_LOW;
    /** @var array */
    private $filters = ['valid_to >= NOW'];

    private function __construct($userId, $count, $scenario, $rotationRate, $rotationTime)
    {
        $this->setUserId($userId);
        $this->setCount($count);
        $this->setScenario($scenario);
        $this->setRotationRate($rotationRate);
        $this->setRotationTime($rotationTime);
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
     * @return static
     */
    public static function create($userId, $count, $scenario, $rotationRate, $rotationTime)
    {
        return new static($userId, $count, $scenario, $rotationRate, $rotationTime);
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
     * @param mixed $minimalRelevance
     * @return $this
     */
    public function setMinimalRelevance($minimalRelevance)
    {
        Assertion::choice($minimalRelevance, [static::MINIMAL_RELEVANCE_LOW, static::MINIMAL_RELEVANCE_MEDIUM, static::MINIMAL_RELEVANCE_HIGH]);
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

    public function getUserId()
    {
        return $this->userId;
    }

    protected function setUserId($userId)
    {
        Assertion::typeIdentifier($userId);
        $this->userId = $userId;
    }

    protected function setCount($count)
    {
        Assertion::greaterThan($count, 0);
        $this->count = $count;
    }

    protected function setScenario($scenario)
    {
        Assertion::typeIdentifier($scenario);
        $this->scenario = $scenario;
    }

    protected function setRotationRate($rotationRate)
    {
        Assertion::between($rotationRate, 0, 1);
        $this->rotationRate = $rotationRate;
    }

    protected function setRotationTime($rotationTime)
    {
        Assertion::greaterThan($rotationTime, 0);
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
        return ['user_id' => $this->userId, 'count' => $this->count, 'scenario' => $this->scenario, 'rotation_rate' => $this->rotationRate, 'rotation_time' => $this->rotationTime, 'hard_rotation' => $this->hardRotation, 'min_relevance' => $this->minimalRelevance, 'filter' => $this->assembleFiltersString()];
    }
}
