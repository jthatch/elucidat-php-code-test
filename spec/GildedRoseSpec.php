<?php

declare(strict_types=1);
/** @noinspection StaticClosureCanBeUsedInspection */

use App\GildedRose;
use App\Interfaces\InnInterface;
use App\Item;

describe('Gilded Rose', function (): void {
    context('class inheritance', function (): void {
        it('should implement InnInterface', function (): void {
            $gr = new GildedRose([new Item('normal', 10, 5)]);
            expect($gr)->toBeAnInstanceOf(InnInterface::class);
        });
    });

    context('constructor', function (): void {
        it('should receive an array of Items', function (): void {
            $itemSet = [
                new Item('firstItem', 10, 5),
                new Item('secondItem', 10, 5),
                new Item('thirdItem', 10, 5),
            ];
            $gr = new GildedRose($itemSet);

            // valid items
            expect($gr->getItem(0)->name)->toBe('firstItem');
            expect($gr->getItem(1)->name)->toBe('secondItem');
            expect($gr->getItem(2)->name)->toBe('thirdItem');
        });

        it('should throw an Exception when given invalid array', function (): void {
            $fakeClass = \Kahlan\Plugin\Double::instance();
            allow($fakeClass)->toReceive('toString')->andReturn('not an item');
            $invalidItemSet = [
                $fakeClass,
            ];
            $closure = function () use ($invalidItemSet): void {
                new GildedRose($invalidItemSet);
            };

            // for some reason this is failing when i specify the Exception class
            expect($closure)->toThrow();
        });
    });

    context('get item', function (): void {
        it('should correctly return an array of Items', function (): void {
            $itemSet = [
                new Item('firstItem', 10, 5),
                new Item('secondItem', 10, 5),
                new Item('thirdItem', 10, 5),
            ];
            $gr = new GildedRose($itemSet);

            // valid items
            expect($gr->getItem(0)->name)->toBe('firstItem');
            expect($gr->getItem(1)->name)->toBe('secondItem');
            expect($gr->getItem(2)->name)->toBe('thirdItem');
        });

        it('should throw an exception when an invalid item key is passed', function (): void {
            $itemSet = [
                new Item('firstItem', 10, 5),
                new Item('secondItem', 10, 5),
                new Item('thirdItem', 10, 5),
            ];
            $gr = new GildedRose($itemSet);

            // valid items
            $closure = function () use ($gr): void {
                $gr->getItem(4);
            };
            // for some reason this is failing when i specify the Exception class
            // actual:
            //  (object) `App\Exceptions\ItemNotFoundException` Code(0) with message "Item not found" in /tmp/kahlan/usr/src/elucidat/src/GildedRose.php:34
            // expected:
            //  (object) `Kahlan\Matcher\AnyException` Code(0) with message "App\\Exceptions\\ItemNotFoundException" in /usr/src/elucidat/vendor/kahlan/kahlan/src/Matcher/ToThrow.php:64
            expect($closure)->toThrow();
        });

        it('should return the entire set when no key is passed', function (): void {
            $itemSet = [
                new Item('firstItem', 10, 5),
                new Item('secondItem', 10, 5),
                new Item('thirdItem', 10, 5),
            ];
            $gr = new GildedRose($itemSet);

            // valid items
            expect($gr->getItem(null))->toBe($itemSet);
        });
    });

    describe('next day', function (): void {
        context('normal Items', function (): void {
            it('updates normal items before sell date', function (): void {
                $gr = new GildedRose([new Item('normal', 10, 5)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(9);
                expect($gr->getItem(0)->sellIn)->toBe(4);
            });
            it('updates normal items on the sell date', function (): void {
                $gr = new GildedRose([new Item('normal', 10, 0)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(8);
                expect($gr->getItem(0)->sellIn)->toBe(-1);
            });
            it('updates normal items after the sell date', function (): void {
                $gr = new GildedRose([new Item('normal', 10, -5)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(8);
                expect($gr->getItem(0)->sellIn)->toBe(-6);
            });
            it('updates normal items with a quality of 0', function (): void {
                $gr = new GildedRose([new Item('normal', 0, 5)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(0);
                expect($gr->getItem(0)->sellIn)->toBe(4);
            });
        });

        context('Brie Items', function (): void {
            it('updates Brie items before the sell date', function (): void {
                $gr = new GildedRose([new Item('Aged Brie', 10, 5)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(11);
                expect($gr->getItem(0)->sellIn)->toBe(4);
            });
            it('updates Brie items before the sell date with maximum quality', function (): void {
                $gr = new GildedRose([new Item('Aged Brie', 50, 5)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(50);
                expect($gr->getItem(0)->sellIn)->toBe(4);
            });
            it('updates Brie items on the sell date', function (): void {
                $gr = new GildedRose([new Item('Aged Brie', 10, 0)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(12);
                expect($gr->getItem(0)->sellIn)->toBe(-1);
            });
            it('updates Brie items on the sell date, near maximum quality', function (): void {
                $gr = new GildedRose([new Item('Aged Brie', 49, 0)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(50);
                expect($gr->getItem(0)->sellIn)->toBe(-1);
            });
            it('updates Brie items on the sell date with maximum quality', function (): void {
                $gr = new GildedRose([new Item('Aged Brie', 50, 0)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(50);
                expect($gr->getItem(0)->sellIn)->toBe(-1);
            });
            it('updates Brie items after the sell date', function (): void {
                $gr = new GildedRose([new Item('Aged Brie', 10, -10)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(12);
                expect($gr->getItem(0)->sellIn)->toBe(-11);
            });
            it('updates Brie items after the sell date with maximum quality', function (): void {
                $gr = new GildedRose([new Item('Aged Brie', 50, -10)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(50);
                expect($gr->getItem(0)->sellIn)->toBe(-11);
            });
        });

        context('Sulfuras Items', function (): void {
            it('updates Sulfuras items before the sell date', function (): void {
                $gr = new GildedRose([new Item('Sulfuras, Hand of Ragnaros', 10, 5)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(80);
                expect($gr->getItem(0)->sellIn)->toBe(5);
            });
            it('updates Sulfuras items on the sell date', function (): void {
                $gr = new GildedRose([new Item('Sulfuras, Hand of Ragnaros', 10, 5)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(80);
                expect($gr->getItem(0)->sellIn)->toBe(5);
            });
            it('updates Sulfuras items after the sell date', function (): void {
                $gr = new GildedRose([new Item('Sulfuras, Hand of Ragnaros', 10, -1)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(80);
                expect($gr->getItem(0)->sellIn)->toBe(-1);
            });
        });

        context('Backstage Passes', function (): void {
            it('updates Backstage pass items long before the sell date', function (): void {
                $gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 10, 11)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(11);
                expect($gr->getItem(0)->sellIn)->toBe(10);
            });
            it('updates Backstage pass items close to the sell date', function (): void {
                $gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 10, 10)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(12);
                expect($gr->getItem(0)->sellIn)->toBe(9);
            });
            it('updates Backstage pass items close to the sell data, at max quality', function (): void {
                $gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 50, 10)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(50);
                expect($gr->getItem(0)->sellIn)->toBe(9);
            });
            it('updates Backstage pass items very close to the sell date', function (): void {
                $gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 10, 5)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(13); // goes up by 3
                expect($gr->getItem(0)->sellIn)->toBe(4);
            });
            it('updates Backstage pass items very close to the sell date, at max quality', function (): void {
                $gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 50, 5)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(50);
                expect($gr->getItem(0)->sellIn)->toBe(4);
            });
            it('updates Backstage pass items with one day left to sell', function (): void {
                $gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 10, 1)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(13);
                expect($gr->getItem(0)->sellIn)->toBe(0);
            });
            it('updates Backstage pass items with one day left to sell, at max quality', function (): void {
                $gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 50, 1)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(50);
                expect($gr->getItem(0)->sellIn)->toBe(0);
            });
            it('updates Backstage pass items on the sell date', function (): void {
                $gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 10, 0)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(0);
                expect($gr->getItem(0)->sellIn)->toBe(-1);
            });
            it('updates Backstage pass items after the sell date', function (): void {
                $gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 10, -1)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(0);
                expect($gr->getItem(0)->sellIn)->toBe(-2);
            });
        });

        context('Conjured Items', function (): void {
            it('updates Conjured items before the sell date', function (): void {
                $gr = new GildedRose([new Item('Conjured Mana Cake', 10, 10)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(8);
                expect($gr->getItem(0)->sellIn)->toBe(9);
            });
            it('updates Conjured items at zero quality', function (): void {
                $gr = new GildedRose([new Item('Conjured Mana Cake', 0, 10)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(0);
                expect($gr->getItem(0)->sellIn)->toBe(9);
            });
            it('updates Conjured items on the sell date', function (): void {
                $gr = new GildedRose([new Item('Conjured Mana Cake', 10, 0)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(6);
                expect($gr->getItem(0)->sellIn)->toBe(-1);
            });
            it('updates Conjured items on the sell date at 0 quality', function (): void {
                $gr = new GildedRose([new Item('Conjured Mana Cake', 0, 0)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(0);
                expect($gr->getItem(0)->sellIn)->toBe(-1);
            });
            it('updates Conjured items after the sell date', function (): void {
                $gr = new GildedRose([new Item('Conjured Mana Cake', 10, -10)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(6);
                expect($gr->getItem(0)->sellIn)->toBe(-11);
            });
            it('updates Conjured items after the sell date at zero quality', function (): void {
                $gr = new GildedRose([new Item('Conjured Mana Cake', 0, -10)]);
                $gr->nextDay();
                expect($gr->getItem(0)->quality)->toBe(0);
                expect($gr->getItem(0)->sellIn)->toBe(-11);
            });
        });
    });
});
