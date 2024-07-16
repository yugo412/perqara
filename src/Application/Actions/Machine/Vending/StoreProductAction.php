<?php

namespace App\Application\Actions\Machine\Vending;

use App\Domain\Machine\Vending;
use Psr\Http\Message\ResponseInterface as Response;
use Valitron\Validator;

class StoreProductAction extends VendingAction
{
    protected function action(): Response
    {
        $validator = new Validator($this->request->getParsedBody());
        $validator->rule('required', ['name', 'price']);
        $validator->rule('numeric', 'price');

        if (!$validator->validate()) {
            return $this->respondWithData([
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ]);
        }

        return $this->respondWithData(
            $this->vendingRepository->store(new Vending(...$validator->data())),
            201
        );
    }
}
