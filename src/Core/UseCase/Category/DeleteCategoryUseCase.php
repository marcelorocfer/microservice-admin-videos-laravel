<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteOutputDTO;

class DeleteCategoryUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    } 

    public function execute(CategoryInputDTO $input): CategoryDeleteOutputDto
    {
        $response = $this->repository->delete($input->id);

        return new CategoryDeleteOutputDTO(
            success: $response,
        );
    }
}