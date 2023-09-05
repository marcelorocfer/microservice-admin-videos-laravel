<?php

namespace Core\UseCase\Video\ChangeEncoded;

use Core\UseCase\Video\DTO\ChangeEncodedVideoDTO;
use Core\Domain\Repository\VideoRepositoryInterface;

class ChangeEncodedPathVideo
{
    public function __construct(
        protected VideoRepositoryInterface $repository
    ) {}

    public function exec(ChangeEncodedVideoDTO $input): void
    {
        $this->repository->findById($input->id);
    }
}
