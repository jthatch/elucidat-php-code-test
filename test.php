<?php

use App\GildedRose;
use App\Item;

include_once('vendor/autoload.php');

$gr = new GildedRose([new Item('Backstage passes to a TAFKAL80ETC concert', 10, 0)]);
$gr->nextDay();
$foo = $gr->getItem(0);
var_dump($foo->quality === 0);