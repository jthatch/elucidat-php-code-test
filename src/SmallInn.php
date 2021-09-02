<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\InnInterface;

/**
 * Could be other types of Inn, however all SmallInns only take `Items[]` in their constructor.
 */
abstract class SmallInn implements InnInterface
{
    protected function assertArrayOfItems(array $items): void
    {
        foreach ($items as $item) {
            if (!$item instanceof Item) {
                throw new \InvalidArgumentException(sprintf('The object %s is not an instance of Item', $item));
            }
        }
    }
}
