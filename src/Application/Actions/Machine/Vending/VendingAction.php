<?php

namespace App\Application\Actions\Machine\Vending;

use App\Application\Actions\Action;
use App\Domain\Machine\VendingRepository;
use Psr\Log\LoggerInterface;

abstract class VendingAction extends Action
{
    public function __construct(LoggerInterface $logger, protected readonly VendingRepository $vendingRepository)
    {
        parent::__construct($logger);
    }
}
