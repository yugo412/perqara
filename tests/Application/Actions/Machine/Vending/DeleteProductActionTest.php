<?php

namespace Application\Actions\Machine\Vending;

use App\Domain\Machine\Product;
use App\Domain\Machine\VendingRepository;
use Tests\TestCase;

class DeleteProductActionTest extends TestCase
{
    public function testDeleteActionProductNotFound(): void
    {
        $app = $this->getAppInstance();

        $water = new Product('Water', 3500);

        $request = $this->createRequest('DELETE', '/vending/product/' . $water->name());
        $response = $app->handle($request);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testDeleteAction(): void
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();

        $water = new Product('Water', 5000);

        $mockRepository = $this->prophesize(VendingRepository::class);
        $mockRepository->remove($water->name())
            ->shouldBeCalledOnce();

        $container->set(VendingRepository::class, $mockRepository->reveal());

        $request = $this->createRequest('DELETE', '/vending/product/' . $water->name());
        $response = $app->handle($request);

        $this->assertSame(204, $response->getStatusCode());
    }
}
