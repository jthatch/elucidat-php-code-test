<?php
/** @noinspection StaticClosureCanBeUsedInspection */

use App\AgedBrieItem;
use App\BackstagePassItem;
use App\ConjuredItem;
use App\Exceptions\ItemNotFoundException;
use App\Interfaces\InnInterface;
use App\Item;
use App\ItemFactory;
use App\NormalItem;
use App\SulfurasItem;

describe('Item Factory', function () {
    context('create', function() {
        it('should return an Aged Brie', function() {
            $item = new Item('aged brie', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(AgedBrieItem::class);
        });

        it('should return a Backstage Pass', function() {
            $item = new Item('Backstage passes to a TAFKAL80ETC concert', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(BackstagePassItem::class);
        });

        it('should return a Sulfuras', function() {
            $item = new Item('Sulfuras, Hand of Ragnaros', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(SulfurasItem::class);
        });

        it('should return a Conjoured', function() {
            $item = new Item('Conjured Mana Cake', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(ConjuredItem::class);
        });

        it('should return a Normal', function() {
            $item = new Item('Normal', 10, 5);
            $specificItem = ItemFactory::create($item);
            expect($specificItem)->toBeAnInstanceOf(NormalItem::class);
        });
    });

    context('create bulk', function() {
        it('should return a Sulfuras and Conjoured', function() {
            $items = [
                new Item('Sulfuras, Hand of Ragnaros', 10, 5),
                new Item('Conjured Mana Cake', 10, 5)
            ];
            $specificItems = ItemFactory::createBulk($items);
            expect($specificItems[0])->toBeAnInstanceOf(SulfurasItem::class);
            expect($specificItems[1])->toBeAnInstanceOf(ConjuredItem::class);
        });
    });
});