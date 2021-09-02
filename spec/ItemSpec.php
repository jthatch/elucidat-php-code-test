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

describe('Item', function (): void {
    context('validate quality', function (): void {
        it('should correctly enforce quality based on Items MIN and MAX quality', function (): void {
            // testing normal
            $specificItemReflection = new \ReflectionClass(NormalItem::class);
            $minQuality = $specificItemReflection->getConstant('MIN_QUALITY');
            $maxQuality = $specificItemReflection->getConstant('MAX_QUALITY');

            $data = [
                '1'   => 1,
                '10'  => 10,

                (string) $maxQuality*2 => $maxQuality,
                (string) $minQuality - 10 => $minQuality,
            ];

            foreach ($data as $quality => $expectedQuality) {
                $quality = (int) $quality;
                $item = new Item('normal', $quality, 10);
                $specificItem = ItemFactory::create($item);
                expect($specificItem->quality)->toBe($expectedQuality);
            }
        });
    });
});