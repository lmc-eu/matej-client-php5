<?php

namespace Lmc\Matej\Model\Command;

/**
 * Commands implementing this interface are aware of user, which is affected by the commands.
 */
interface UserAwareInterface
{
    public function getUserId();
}
