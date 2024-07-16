<?php

namespace App\Infrastructure\Persistence\Machine;

use App\Domain\Machine\Vending;
use App\Domain\Machine\VendingRepository;

class RedisVendingRepository implements VendingRepository
{

    public function store(Vending $store): Vending
    {
        // TODO: Implement store() method.
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function get(string $name): ?Vending
    {
        // TODO: Implement get() method.
    }
}