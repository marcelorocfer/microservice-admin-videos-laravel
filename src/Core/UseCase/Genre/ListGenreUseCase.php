<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\DTO\Genre\GenreOutputDTO;

class ListGenreUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GenreInputDTO $input): GenreOutputDTO
    {
        $genre = $this->repository->findById(id: $input->id);

        return new GenreOutputDTO(
            id: (string) $genre->id,
            name: $genre->name,
            is_active: $genre->is_active,
            created_at: $genre->created_at(),
        );
    }
}
