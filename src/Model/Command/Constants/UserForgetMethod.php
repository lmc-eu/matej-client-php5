<?php

namespace Lmc\Matej\Model\Command\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static UserForgetMethod ANONYMIZE()
 * @method static UserForgetMethod DELETE()
 */
final class UserForgetMethod extends Enum
{
    const ANONYMIZE = 'anonymize';
    const DELETE = 'delete';
}
