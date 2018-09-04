<?php

namespace Lmc\Matej\Model\Command\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static MinimalRelevance LOW()
 * @method static MinimalRelevance MEDIUM()
 * @method static MinimalRelevance HIGH()
 */
final class MinimalRelevance extends Enum
{
    const LOW = 'low';
    const MEDIUM = 'medium';
    const HIGH = 'high';
}
