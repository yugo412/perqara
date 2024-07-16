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

    private function isValidCombination(float $total): bool
    {
        arsort($this->denominations, SORT_NUMERIC);
        foreach ($this->denominations as $amount) {
            $total = $total - (floor($total / $amount) * $amount);
        }

        return $total === 0.0;
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

            return $this->isValidCombination(array_sum($value));
        }, 'amount')->message(sprintf('{field} only accepts denominations: %s', implode(', ', $this->denominations)));

        if (!$validator->validate()) {
            return $this->respondWithData([
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        return $this->respondWithData([], 201);
    }
}
