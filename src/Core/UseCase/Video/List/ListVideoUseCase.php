<?php

namespace Core\UseCase\Video\List;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\DTO\ListInputVideoUseCase;
use Core\UseCase\Video\List\DTO\ListOutputVideoUseCase;

class ListVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {}

    public function exec(ListInputVideoUseCase $input): ListOutputVideoUseCase
    {

    }
}
