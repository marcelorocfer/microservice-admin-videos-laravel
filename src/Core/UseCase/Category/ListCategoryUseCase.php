<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryInputDTO $input): CategoryOutputDTO
    {
        $category = $this->repository->findById($input->id);

        return new CategoryOutputDTO(
            id: $category->id(),
            name: $category->name,
            description: $category->description,
            isActive: $category->isActive,
            createdAt: $category->createdAt(),
        );
    }
}