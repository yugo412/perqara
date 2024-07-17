<?php

namespace Infrastructure\Persistence\Machine;

use App\Domain\Machine\Product;
use App\Infrastructure\Persistence\Machine\InMemoryVendingRepository;
use Tests\TestCase;

class InMemoryVendingRepositoryTest extends TestCase
{
    public function testGetAll(): void
    {
        $cola = new Product('Cola', 3000);

        $repository = new InMemoryVendingRepository($cola);

        $this->assertEquals([$cola], $repository->getAll());
    }

    public function testStoreProduct(): void
    {
        $pepsi = new Product('Pepsi', 3000);
        $repository = new InMemoryVendingRepository($pepsi);
        $storedProduct = $repository->store($cola = new Product('Cola', 3000));

        $this->assertEquals($cola, $storedProduct);
        $this->assertEquals([$pepsi, $cola], $repository->getAll());
    }

    public function testGetProduct(): void
    {
        $water = new Product($name = 'Water', 3000);
        $repository = new InMemoryVendingRepository($water);

        $this->assertEquals($water, $repository->get($name));
    }

    public function testRemoveProduct(): void
    {
        $repository = new InMemoryVendingRepository();
        $repository->store(new Product('Cola', 3000));
        $pepsi = $repository->store(new Product($name = 'Pepsi', 3000));

        $repository->remove($name);

        $this->assertNotContains($pepsi, $repository->getAll());
    }
}
