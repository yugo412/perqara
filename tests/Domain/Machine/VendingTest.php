<?php

namespace Domain\Machine;

use App\Domain\Machine\Product;
use Tests\TestCase;

class VendingTest extends TestCase
{
    private function productProvider(): array
    {
        $items = [
            'Aqua' => 1000,
            'Sosro' => 5000,
            'Cola' => 7000,
            'Milo' => 9000,
            'Coffee' => 12000,
        ];

        return array_map(function (float $price, string $name): array {
            return compact('price', 'name');
        }, $items, array_keys($items));
    }

    /**
     * @dataProvider productProvider
     * @param float $price
     * @param string $name
     * @return void
     */
    public function testGetter(float $price, string $name): void
    {
        $product = new Product($name, $price);

        $this->assertEquals($price, $product->price());
        $this->assertEquals($name, $product->name());
    }

    /**
     * @dataProvider productProvider
     * @param float $price
     * @param string $name
     * @return void
     */
    public function testJsonSerialize(float $price, string $name): void
    {
        $product = new Product($name, $price);

        $expectation = json_encode([
            'name' => $name,
            'price' => $price,
        ]);

        $this->assertEquals($expectation, json_encode($product->jsonSerialize()));
    }
}
