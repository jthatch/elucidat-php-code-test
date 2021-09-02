<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\ItemInterface;

final class ConjuredItem extends ItemBehaviour implements ItemInterface
{
    protected const MIN_QUALITY          = 0;
    protected const MAX_QUALITY          = 50;
    protected const PRE_QUALITY_DEGRADE  = 2;
    protected const POST_QUALITY_DEGRADE = 4;
    protected const SELL_IN_COUNTER      = 1;
}
