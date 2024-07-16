<?php

namespace App\Domain\Machine;

interface VendingRepository
{
    public function store(Vending $store): Vending;

    public function getAll(): array;

    public function get(string $name): ?Vending;

    public function remove(string $name): void;
}
