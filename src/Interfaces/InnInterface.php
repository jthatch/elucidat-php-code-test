<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Item;

interface InnInterface
{
    public function getItem(int $which = null): array|Item|ItemInterface;

    public function nextDay(): void;
}
