<?php

namespace App\Application\Actions\Machine\Vending;

use Psr\Http\Message\ResponseInterface as Response;

class ListProductAction extends VendingAction
{
    protected function action(): Response
    {
        return $this->respondWithData($this->vendingRepository->getAll());
    }
}
