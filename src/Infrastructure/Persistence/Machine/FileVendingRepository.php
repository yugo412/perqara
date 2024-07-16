<?php

namespace App\Infrastructure\Persistence\Machine;

use App\Domain\Machine\Order;
use App\Domain\Machine\Vending;
use App\Domain\Machine\VendingRepository;
use Exception;

class FileVendingRepository implements VendingRepository
{
    private string $path = '../storage/products.json';

    /**
     * @throws Exception
     */
    public function store(Vending $product): Vending
    {
        $products = $this->read();

        // check if product is already exist
        $index = array_search($product->name(), array_column($products, 'name'));
        if ($index !== false) {
            // update existing product
            $products[$index] = $product->jsonSerialize();
        } else {
            // append a new one
            array_push($products, $product->jsonSerialize());
        }

        $this->write($products);

        return $product;
    }

    /**
     * @throws Exception
     */
    public function getAll(): array
    {
        return array_map(function ($product) {
            return new Vending(...$product);
        }, $this->read());
    }

    /**
     * @throws Exception
     */
    public function get(string $name): ?Vending
    {
        $products = $this->read();

        $index = array_search($name, array_column($products, 'name'));
        if ($index === false) {
            throw new Exception('Product not found.', 404);
        }

        return new Vending(...$products[$index]);
    }

    /**
     * @throws Exception
     */
    public function remove(string $name): void
    {
        $products = $this->read();
        $index = array_search($name, array_column($this->read(), 'name'));
        if ($index === false) {
            throw new Exception('Product not found.', 404);
        }

        if (!empty($products[$index])) {
            unset($products[$index]);
            $this->write($products);
        }
    }

    /**
     * @throws Exception
     */
    public function order(float $total): Order
    {
        $orders = [];
        $change = $total;

        $products = $this->read();
        usort($products, fn($a, $b): int => $b['price'] - $a['price']);
        foreach ($products as ['price' => $price, 'name' => $name]) {
            if ($change < 0) {
                break;
            }

            $count = intval(floor($change / $price));

            if ($count >= 1) {
                $orders = array_merge(
                    $orders,
                    array_fill(empty($orders) ? 0 : count($orders), $count, $name),
                );

                $change -= $count * $price;
            }
        }

        return new Order($orders, $total, $change);
    }

    private function write(array $contents): void
    {
        usort($contents, function ($a, $b) {
            return $a['price'] <=> $b['price'];
        });

        file_put_contents($this->path, json_encode($contents, JSON_PRETTY_PRINT));
    }

    /**
     * @throws Exception
     */
    private function read(): array
    {
        $path = realpath($this->path);
        if ($path === false) {
            throw new Exception('File products.json not found.');
        }

        $contents = file_get_contents(realpath($this->path));
        if (!json_validate($contents)) {
            throw new Exception('File contains invalid JSON format.');
        }

        return json_decode($contents, true);
    }
}
