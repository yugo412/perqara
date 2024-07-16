<?php

namespace App\Application\Actions\Machine\Vending;

use Psr\Http\Message\ResponseInterface as Response;
use Valitron\Validator;

class OrderProductAction extends VendingAction
{
    private array $denominations = [
        2000,
        5000,
    ];

    private function combination(array $denominations, int $n, float $sum): int
    {
        if ($sum === floatval(0)) {
            return 1;
        }

        if ($sum < 0) {
            return 0;
        }

        if ($n <= 0) {
            return 0;
        }

        return $this->combination($denominations, $n - 1, $sum) +
            $this->combination($denominations, $n, $sum - $denominations[$n - 1]);
    }

    protected function action(): Response
    {
        $validator = new Validator($this->request->getParsedBody());
        $validator->rule(function (string $field, mixed $value): bool {
            unset($field);

            return !empty($value);
        }, 'amount')->message('{field} is required');
        $validator->rule('array', 'amount');
        $validator->rule('numeric', 'amount.*');
        $validator->rule('min', 'amount.*', min($this->denominations));
        $validator->rule('in', 'amount.*', $this->denominations);
        $validator->rule(function (string $field, array $value): bool {
            unset($field);

            return $this->combination($this->denominations, count($this->denominations), array_sum($value)) >= 1;
        }, 'amount')->message(sprintf('{field} only accepts denominations: %s', implode(', ', $this->denominations)));

        if (!$validator->validate()) {
            return $this->respondWithData([
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $total = array_sum($validator->data()['amount']);

        return $this->respondWithData($this->vendingRepository->order($total), 201);
    }
}
