<?php

namespace App\Application\Actions\Machine\Vending;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteProductAction extends VendingAction
{
    protected function action(): Response
    {
        try {
            $this->vendingRepository->remove($this->resolveArg('name'));

            return $this->response->withStatus(204);
        } catch (Exception $e) {
            return $this->respondWithData([
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
