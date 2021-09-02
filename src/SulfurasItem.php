<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\ItemInterface;

final class SulfurasItem extends ItemBehaviour implements ItemInterface
{
    protected const MIN_QUALITY          = 80;
    protected const MAX_QUALITY          = 80;
    protected const PRE_QUALITY_DEGRADE  = 0;
    protected const POST_QUALITY_DEGRADE = 0;
    protected const SELL_IN_COUNTER      = 0;
}
