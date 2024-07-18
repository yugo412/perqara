<?php

namespace Application\Actions\Machine\Vending;

use App\Application\Actions\ActionPayload;
use App\Domain\Machine\Product;
use App\Domain\Machine\VendingRepository;
use Tests\TestCase;

class StoreProductActionTest extends TestCase
{
    public function testActionValidationFailed(): void
    {
        $app = $this->getAppInstance();

        // no request body provided
        $request = $this->createRequest('POST', '/vending');
        $response = $app->handle($request);

        $expected = [
            'statusCode' => 422,
            'data' => [
                'message' => 'Validation errors.',
                'errors' => [
                    'name' => [
                        'Name is required',
                    ],
                    'price' => [
                        'Price is required',
                        'Price must be numeric',
                    ],
                ],
            ]
        ];

        $this->assertSame(422, $response->getStatusCode());
        $this->assertEquals(json_encode($expected, JSON_PRETTY_PRINT), (string) $response->getBody());
    }

    /**
     * @throws \Exception
     */
    public function testAction(): void
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();

        $cola = new Product('Cola', 3500);

        $mockRepository = $this->prophesize(VendingRepository::class);
        $mockRepository->store($cola)
            ->willReturn($cola)
            ->shouldBeCalledOnce();

        $container->set(VendingRepository::class, $mockRepository->reveal());

        $request = $this->createRequest('POST', '/vending');
        $request = $request->withParsedBody($cola->jsonSerialize());

        $response = $app->handle($request);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertEquals(
            json_encode(new ActionPayload(201, $cola), JSON_PRETTY_PRINT),
            (string) $response->getBody(),
        );
    }
}
