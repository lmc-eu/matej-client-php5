<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Model\Assertion;
use Lmc\Matej\Model\Command\AbstractCommand;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserRecommendation;
use Lmc\Matej\Model\Request;

class CampaignRequestBuilder extends AbstractRequestBuilder
{
    const ENDPOINT_PATH = '/campaign';
    /** @var AbstractCommand[] */
    protected $commands = [];

    /** @return $this */
    public function addRecommendation(UserRecommendation $recommendation)
    {
        $this->commands[] = $recommendation;

        return $this;
    }

    /**
     * @param UserRecommendation[] $recommendations
     * @return $this
     */
    public function addRecommendations(array $recommendations)
    {
        foreach ($recommendations as $recommendation) {
            $this->addRecommendation($recommendation);
        }

        return $this;
    }

    /** @return $this */
    public function addSorting(Sorting $sorting)
    {
        $this->commands[] = $sorting;

        return $this;
    }

    /**
     * @param Sorting[] $sortings
     * @return $this
     */
    public function addSortings(array $sortings)
    {
        foreach ($sortings as $sorting) {
            $this->addSorting($sorting);
        }

        return $this;
    }

    public function build()
    {
        if (empty($this->commands)) {
            throw new LogicException('At least one command must be added to the builder before sending the request');
        }
        Assertion::batchSize($this->commands);

        return new Request(static::ENDPOINT_PATH, RequestMethodInterface::METHOD_POST, $this->commands, $this->requestId);
    }
}
