<?php

namespace Lmc\Matej\Model\Command\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static PropertyType INT()
 * @method static PropertyType DOUBLE()
 * @method static PropertyType STRING()
 * @method static PropertyType BOOLEAN()
 * @method static PropertyType TIMESTAMP()
 * @method static PropertyType SET()
 */
final class PropertyType extends Enum
{
    const INT = 'int';
    const DOUBLE = 'double';
    const STRING = 'string';
    const BOOLEAN = 'boolean';
    const TIMESTAMP = 'timestamp';
    const SET = 'set';
}
