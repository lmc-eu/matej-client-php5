<?php

namespace Lmc\Matej\Model\Command;

abstract class AbstractCommand implements \JsonSerializable
{
    /**
     * Get command type identifier. Must be one of those defined by Matej API schema.
     */
    abstract protected function getCommandType();

    /**
     * Get data content of the command. Must follow the format defined by Matej API schema.
     */
    abstract protected function getCommandParameters();

    public function jsonSerialize()
    {
        return ['type' => $this->getCommandType(), 'parameters' => $this->getCommandParameters()];
    }
}
