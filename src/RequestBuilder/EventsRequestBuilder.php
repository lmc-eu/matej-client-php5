<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Model\Command\AbstractCommand;
use Lmc\Matej\Model\Command\Interaction;
use Lmc\Matej\Model\Command\ItemProperty;
use Lmc\Matej\Model\Command\UserMerge;
use Lmc\Matej\Model\Request;

class EventsRequestBuilder extends AbstractRequestBuilder
{
    const ENDPOINT_PATH = '/events';
    /** @var AbstractCommand[] */
    protected $commands = [];

    public function addInteraction(Interaction $interaction)
    {
        $this->commands[] = $interaction;

        return $this;
    }

    /**
     * @param Interaction[] $interactions
     * @return self
     */
    public function addInteractions(array $interactions)
    {
        foreach ($interactions as $interaction) {
            $this->addInteraction($interaction);
        }

        return $this;
    }

    public function addItemProperty(ItemProperty $itemProperty)
    {
        $this->commands[] = $itemProperty;

        return $this;
    }

    /**
     * @param ItemProperty[] $itemProperties
     * @return self
     */
    public function addItemProperties(array $itemProperties)
    {
        foreach ($itemProperties as $itemProperty) {
            $this->addItemProperty($itemProperty);
        }

        return $this;
    }

    public function addUserMerge(UserMerge $userMerge)
    {
        $this->commands[] = $userMerge;

        return $this;
    }

    /**
     * @param UserMerge[] $userMerges
     * @return self
     */
    public function addUserMerges(array $userMerges)
    {
        foreach ($userMerges as $userMerge) {
            $this->addUserMerge($userMerge);
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
