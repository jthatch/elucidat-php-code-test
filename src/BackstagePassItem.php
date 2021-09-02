<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\ItemInterface;

class BackstagePassItem extends ItemBehaviour implements ItemInterface
{
    protected const MIN_QUALITY          = 0;
    protected const MAX_QUALITY          = 50;
    protected const PRE_QUALITY_DEGRADE  = -1;
    protected const POST_QUALITY_DEGRADE = 2;
    protected const SELL_IN_COUNTER      = 1;

    protected function getQualityChange(): int
    {
        if ($this->item->sellIn > 0) {
            if ($this->item->sellIn <= 5) {
                return $this->preQualityDegradeMultiplier * 3;
            }

            if ($this->item->sellIn <= 10) {
                return $this->preQualityDegradeMultiplier * 2;
            }

            return $this->preQualityDegradeMultiplier;
        }

        $this->item->quality = 0;

        return 0;
    }
}
