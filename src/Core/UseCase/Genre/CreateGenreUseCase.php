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
use Core\Domain\Entity\Genre;
use Core\Domain\Exceptions\NotFoundException;
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
        try {
            $genre = new Genre(
                name: $input->name,
                is_active: $input->is_active,
                categoriesId: $input->categoriesId
            );

            $this->validateCategoriesId($input->categoriesId);

            $genreDB = $this->repository->insert($genre);
        
            return new GenreCreateOutputDTO(
                id: (string) $genreDB->id,
                name: $genreDB->name,
                is_active: $genreDB->is_active,
                created_at: $genreDB->created_at(),
            );

            $this->transaction->commit();
        } catch (\Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }       
    }

    public function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDB = $this->categoryRepository->getIdsListIds($categoriesId);

        if (count($categoriesDB) !== count($categoriesId)) {
            throw new NotFoundException('Categories Not Found!');
        }
    }
}