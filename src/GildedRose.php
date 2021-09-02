<?php

namespace App;

use App\Exceptions\ItemNotFoundException;
use App\Interfaces\ItemInterface;

class GildedRose extends SmallInn
{
    /**
     * @var Item[] $items
     */
    private array $items;

    /**
     * @param Item[] $items
     */
    public function __construct(array $items)
    {
        $this->assertArrayOfItems($items);
        $this->items = ItemFactory::createBulk($items);
    }

    /**
     * Returns an Item instance if key is specified otherwise returns the Item[] set
     *
     * @param int|null $which
     * @return array|Item
     * @throws ItemNotFoundException
     */
    public function getItem(int $which = null): array|Item|ItemInterface
    {
        return ($which === null
            ? $this->items
            : $this->items[$which] ?? throw new ItemNotFoundException('Item not found')
        );
    }

    public function nextDay(): void
    {
        array_walk($this->items, function(ItemInterface $item) {
            $item->nextDay();
        });
    }

    public function nextDayOld(): void
    {
        foreach ($this->items as $item) {
            if ($item->name != 'Aged Brie' && $item->name != 'Backstage passes to a TAFKAL80ETC concert') {
                if ($item->quality > 0) {
                    if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                        $item->quality = $item->quality - 1;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                    if ($item->name == 'Backstage passes to a TAFKAL80ETC concert') {
                        if ($item->sellIn < 11) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                        if ($item->sellIn < 6) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                    }
                }
            }
            if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                $item->sellIn = $item->sellIn - 1;
            }
            if ($item->sellIn < 0) {
                if ($item->name != 'Aged Brie') {
                    if ($item->name != 'Backstage passes to a TAFKAL80ETC concert') {
                        if ($item->quality > 0) {
                            if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                                $item->quality = $item->quality - 1;
                            }
                        }
                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                }
            }
        }
    }
}
