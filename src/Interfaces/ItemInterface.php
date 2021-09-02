<?php

namespace App\Interfaces;

interface ItemInterface
{
    public function __toString(): string;

    public function nextDay(): int;
}