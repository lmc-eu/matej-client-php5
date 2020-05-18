<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Assertion;

/**
 * Boosting items is a way how to modify results returend by Matej by specifying
 * rules to increase items relevance.
 */
class Boost
{
    /** @var string */
    private $query;
    /** @var float */
    private $multiplier;

    private function __construct($query, $multiplier)
    {
        $this->setQuery($query);
        $this->setMultiplier($multiplier);
    }

    /**
     * Create boost rule to prioritize items
     *
     * @param mixed $query
     * @param mixed $multiplier
     * @return static
     */
    public static function create($query, $multiplier)
    {
        return new static($query, $multiplier);
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function setMultiplier($multiplier)
    {
        Assertion::greaterThan($multiplier, 0);
        $this->multiplier = $multiplier;
    }

    public function jsonSerialize()
    {
        return ['query' => $this->query, 'multiplier' => $this->multiplier];
    }
}
