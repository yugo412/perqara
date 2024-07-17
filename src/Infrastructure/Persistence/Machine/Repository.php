<?php

namespace App\Infrastructure\Persistence\Machine;

use App\Domain\Machine\Product;

abstract class Repository
{
    protected function find(array $items, string $key, mixed $value): ?array
    {
        $index = $this->findIndex($items, $key, $value);
        if ($index !== false) {
            return $items[$index]->jsonSerialize();
        }

        return null;
    }

    protected function findIndex(array $items, string $key, mixed $value): int|false
    {
        $items = array_map(function (mixed $item): array {
            if ($item instanceof Product) {
                return $item->jsonSerialize();
            }

            return $item;
        }, $items);

        return array_search($value, array_column($items, $key));
    }

    protected function processOrder(array $items, float $total): array
    {
        $orders = [];
        $change = $total;

        usort($items, fn($a, $b): int => $b['price'] - $a['price']);
        foreach ($items as ['price' => $price, 'name' => $name]) {
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

        return [
            'products' => $orders,
            'total' => $total,
            'change' => $change,
        ];
    }
}
