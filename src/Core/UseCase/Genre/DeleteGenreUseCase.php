<?php

namespace Core\UseCase\Genre;

use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\Delete\DeleteGenreOutputDTO;

class DeleteGenreUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GenreInputDTO $input): DeleteGenreOutputDTO
    {
        $success = $this->repository->delete($input->id);

        return new DeleteGenreOutputDTO(
            success: $success,
        );
    }
}