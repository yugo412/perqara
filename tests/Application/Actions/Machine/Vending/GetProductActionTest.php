<?php

namespace Application\Actions\Machine\Vending;

use App\Domain\Machine\Product;
use App\Domain\Machine\VendingRepository;
use Tests\TestCase;

class GetProductActionTest extends TestCase
{
    public function testGetProduct(): void
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();

        $cookie = new Product('Cookie', 10000);

        $mockRepository = $this->prophesize(VendingRepository::class);
        $mockRepository->get($cookie->name())
            ->willReturn($cookie)
            ->shouldBeCalledOnce();

        $container->set(VendingRepository::class, $mockRepository->reveal());

        $request = $this->createRequest('GET', '/vending/product/' . $cookie->name());
        $response = $app->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertEquals(
            json_encode([
                'statusCode' => 200,
                'data' => $cookie->jsonSerialize(),
            ], JSON_PRETTY_PRINT),
            (string) $response->getBody(),
        );
    }
}
