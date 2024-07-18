<?php

namespace Application\Actions\Machine\Vending;

use App\Domain\Machine\Product;
use App\Domain\Machine\VendingRepository;
use Tests\TestCase;

class ListProductActionTest extends TestCase
{
    public function testAction(): void
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();

        $cola = new Product('Cola', 1000);

        $mockRepository = $this->prophesize(VendingRepository::class);
        $mockRepository->getAll()
            ->willReturn([$cola])
            ->shouldBeCalledOnce();

        $container->set(VendingRepository::class, $mockRepository->reveal());

        $request = $this->createRequest('GET', '/vending');
        $response = $app->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertEquals(
            json_encode([
                'statusCode' => 200,
                'data' => [$cola->jsonSerialize()],
            ], JSON_PRETTY_PRINT),
            (string) $response->getBody(),
        );
    }
}