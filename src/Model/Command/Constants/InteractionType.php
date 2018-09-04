<?php

namespace Lmc\Matej\Model\Command\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static InteractionType DETAILVIEWS()
 * @method static InteractionType PURCHASES()
 * @method static InteractionType BOOKMARKS()
 * @method static InteractionType RATINGS()
 */
final class InteractionType extends Enum
{
    const DETAILVIEWS = 'detailviews';
    const PURCHASES = 'purchases';
    const BOOKMARKS = 'bookmarks';
    const RATINGS = 'ratings';
}
