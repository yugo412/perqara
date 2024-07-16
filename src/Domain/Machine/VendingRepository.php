<?php

namespace App\Domain\Machine;

interface VendingRepository
{
    public function store(Product $store): Product;

    public function getAll(): array;

    public function get(string $name): ?Product;

    public function remove(string $name): void;

    public function order(float $total): Order;
}
