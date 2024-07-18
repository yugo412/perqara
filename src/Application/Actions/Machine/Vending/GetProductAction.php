<?php

namespace App\Application\Actions\Machine\Vending;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class GetProductAction extends VendingAction
{
    public function action(): Response
    {
        try {
            return $this->respondWithData(
                $this->vendingRepository->get($this->resolveArg('name')),
            );
        } catch (Exception $e) {
            return $this->respondWithData([
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
