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
        $this->minQuality                   = static::MIN_QUALITY;
        $this->maxQuality                   = static::MAX_QUALITY;
        $this->preQualityDegradeMultiplier  = static::PRE_QUALITY_DEGRADE;
        $this->postQualityDegradeMultiplier = static::POST_QUALITY_DEGRADE;
        $this->sellInCounter                = static::SELL_IN_COUNTER;
        $this->item                         = $this->validateItemQuality($item);
    }

    final public function nextDay(): int
    {
        // modify our quality score
        $this->item->quality -= $this->getQualityChange();

        // ensure we haven't exceeded our quality scores
        $this->item = $this->validateItemQuality($this->item);

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

    private function validateItemQuality(Item $item): Item
    {
        $item->quality = max(min($item->quality, $this->maxQuality), $this->minQuality);

        return $item;
    }
}
