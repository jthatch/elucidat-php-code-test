<?php

declare(strict_types=1);
/** @noinspection StaticClosureCanBeUsedInspection */

use App\AgedBrieItem;
use App\BackstagePassItem;
use App\ConjuredItem;
use App\Item;
use App\ItemFactory;
use App\NormalItem;
use App\SulfurasItem;

describe('Item Factory', function (): void {
    context('create', function (): void {
        it('should return an Aged Brie', function (): void {
            $item = new Item('aged brie', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(AgedBrieItem::class);
        });

        it('should return a Backstage Pass', function (): void {
            $item = new Item('Backstage passes to a TAFKAL80ETC concert', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(BackstagePassItem::class);
        });

        it('should return a Sulfuras', function (): void {
            $item = new Item('Sulfuras, Hand of Ragnaros', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(SulfurasItem::class);
        });

        it('should return a Conjoured', function (): void {
            $item = new Item('Conjured Mana Cake', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(ConjuredItem::class);
        });

        it('should return a Normal', function (): void {
            $item = new Item('Normal', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(NormalItem::class);
        });
    });

    context('create bulk', function (): void {
        it('should return a Sulfuras and Conjoured', function (): void {
            $items = [
                new Item('Sulfuras, Hand of Ragnaros', 10, 5),
                new Item('Conjured Mana Cake', 10, 5),
            ];
            $specificItems = ItemFactory::createBulk($items);
            expect($specificItems[0])->toBeAnInstanceOf(SulfurasItem::class);
            expect($specificItems[1])->toBeAnInstanceOf(ConjuredItem::class);
        });
    });
});
