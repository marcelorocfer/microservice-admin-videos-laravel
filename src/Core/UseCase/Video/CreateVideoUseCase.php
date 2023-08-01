<?php

namespace Core\UseCase\Video;

use Core\UseCase\Interfaces\TransactionInterface;
use Core\Domain\Repository\VideoRepositoryInterface;

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction
    ) {}
}
