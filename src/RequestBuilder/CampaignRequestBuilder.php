<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Model\Command\AbstractCommand;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserRecommendation;
use Lmc\Matej\Model\Request;

class CampaignRequestBuilder extends AbstractRequestBuilder
{
    const ENDPOINT_PATH = '/campaign';
    /** @var AbstractCommand[] */
    protected $commands = [];

    public function addRecommendation(UserRecommendation $recommendation)
    {
        $this->commands[] = $recommendation;

        return $this;
    }

    /**
     * @param UserRecommendation[] $recommendations
     * @return self
     */
    public function addRecommendations(array $recommendations)
    {
        foreach ($recommendations as $recommendation) {
            $this->addRecommendation($recommendation);
        }

        return $this;
    }

    public function addSorting(Sorting $sorting)
    {
        $this->commands[] = $sorting;

        return $this;
    }

    /**
     * @param Sorting[] $sortings
     * @return self
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

        return new Request(self::ENDPOINT_PATH, RequestMethodInterface::METHOD_POST, $this->commands);
    }
}
