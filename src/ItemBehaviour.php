<?php

declare(strict_types=1);

namespace App;

abstract class ItemBehaviour
{
    protected Item $item;
    protected int $minQuality;
    protected int $maxQuality;
    protected int $preQualityDegradeMultiplier;
    protected int $postQualityDegradeMultiplier;
    protected int $sellInCounter;

    public function __construct(Item $item)
    {
        $this->item                         = $item;
        $this->minQuality                   = static::MIN_QUALITY;
        $this->maxQuality                   = static::MAX_QUALITY;
        $this->preQualityDegradeMultiplier  = static::PRE_QUALITY_DEGRADE;
        $this->postQualityDegradeMultiplier = static::POST_QUALITY_DEGRADE;
        $this->sellInCounter                = static::SELL_IN_COUNTER;
    }

    /**
     * Can be overridden.
     *
     * @return int
     */
    protected function getQualityChange(): int
    {
        return $this->item->sellIn > 0
            ? $this->preQualityDegradeMultiplier
            : $this->postQualityDegradeMultiplier;
    }

    public function nextDay(): int
    {
        // modify our quality score
        $this->item->quality -= $this->getQualityChange();

        // ensure we haven't exceeded our quality scores
        $this->item->quality  = max($this->item->quality, $this->minQuality);
        $this->item->quality  = min($this->item->quality, $this->maxQuality);

        // decrease our sellIn date by N
        $this->item->sellIn -= $this->sellInCounter;

        return $this->item->quality;
    }

    public function __toString(): string
    {
        return "{$this->item->name}, {$this->item->sellIn}, {$this->item->quality}";
    }

    public function __get(string $name)
    {
        return $this->item->{$name} ?? '';
    }
}
