<?php

namespace Core\UseCase\Genre;

use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\Domain\Repository\GenreRepositoryInterface;

class DeleteGenreUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}