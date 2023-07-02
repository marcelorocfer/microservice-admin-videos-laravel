<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\{
    GenreRepositoryInterface,
    CategoryRepositoryInterface,
};
use Core\UseCase\DTO\Genre\Create\{
    GenreCreateInputDTO, 
    GenreCreateOutputDTO
};
use Core\UseCase\Interfaces\TransactionInterface;

class CreateGenreUseCase 
{
    protected $repository;
    protected $transaction;
    protected $categoryRepository;

    public function __construct(
        GenreRepositoryInterface $repository,
        TransactionInterface $transaction,
        CategoryRepositoryInterface $categoryRepository,
    ) {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(GenreCreateInputDTO $input): GenreCreateOutputDTO
    {

    }
}