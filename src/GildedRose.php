<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ItemNotFoundException;
use App\Interfaces\ItemInterface;

class GildedRose extends SmallInn
{
    /**
     * @var Item[]
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
     * Returns an Item instance if key is specified otherwise returns the Item[] set.
     *
     * @param int|null $which
     *
     * @return array|Item|ItemInterface
     *
     * @throws ItemNotFoundException
     */
    public function getItem(int $which = null): array|Item|ItemInterface
    {
        return null === $which
            ? $this->items
            : $this->items[$which] ?? throw new ItemNotFoundException('Item not found')
        ;
    }

    public function nextDay(): void
    {
        array_walk($this->items, static function (ItemInterface $item): void {
            $item->nextDay();
        });
    }
}
