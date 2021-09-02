<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ItemInterface
{
    public function __toString(): string;

    public function nextDay(): int;
}
