<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateOutputDTO;

class UpdateCategoryUseCase
{
    protected $repository;
    
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryUpdateInputDTO $input): CategoryUpdateOutputDTO
    {
        $category = $this->repository->findById($input->id);

        $category->update(
            name: $input->name,
            description: $input->description ?? $category->description,
        );

        $categoryUpdated = $this->repository->update($category);

        return new CategoryUpdateOutputDTO(
            id: $categoryUpdated->id,
            name: $categoryUpdated->name,
            description: $categoryUpdated->description,
            isActive: $categoryUpdated->isActive,
            createdAt: $categoryUpdated->createdAt(),
        );
    }
}