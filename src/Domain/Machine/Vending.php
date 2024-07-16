<?php

namespace App\Domain\Machine;

use JsonSerializable;

readonly class Vending implements JsonSerializable
{
    public function __construct(
        private string $name,
        private float $price,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
        ];
    }
}
