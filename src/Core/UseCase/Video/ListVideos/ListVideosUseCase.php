<?php

namespace Core\UseCase\Video\ListVideos;

use Core\Domain\Repository\VideoRepositoryInterface;

class ListVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {}
}
