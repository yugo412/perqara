<?php

namespace App\Infrastructure\Persistence\Machine;

use App\Domain\Machine\Order;
use App\Domain\Machine\Product;
use App\Domain\Machine\VendingRepository;

class InMemoryVendingRepository extends Repository implements VendingRepository
{
    private array $products = [];

    public function __construct(private readonly ?Product $product = null)
    {
        if (!empty($this->product)) {
            $this->products[] = $this->product;
        }
    }

    public function store(Product $store): Product
    {
        $this->products = array_merge($this->products, [$store]);

        return $store;
    }

    public function getAll(): array
    {
        return $this->products;
    }

    public function get(string $name): ?Product
    {
        $product = $this->find($this->products, 'name', $name);
        if (!empty($product)) {
            return new Product(...$product);
        }

        return null;
    }

    public function remove(string $name): void
    {
        $index = $this->findIndex($this->products, 'name', $name);
        if ($index !== false) {
            unset($this->products[$index]);
        }
    }

    public function order(float $total): Order
    {
        return new Order(...$this->processOrder($this->products, $total));
    }
}
