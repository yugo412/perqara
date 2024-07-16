<?php

namespace App\Domain\Machine;

use JsonSerializable;

readonly class Order implements JsonSerializable
{
    public function __construct(
        private array $products,
        private float $total,
        private float $change,
    )
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'products' => $this->products,
            'total' => $this->total,
            'change' => $this->change,
        ];
    }
}