<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDTO;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesOutputDTO;

class ListCategoriesUseCase 
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    public function execute(ListCategoriesInputDTO $input): ListCategoriesOutputDTO
    {
        $categories = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage,
        );

        return new ListCategoriesOutputDTO(
            items: $categories->items(),
            total: $categories->total(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            current_page: $categories->currentPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from(),
        );

        /* return new ListCategoriesOutputDTO(
            items: array_map(function($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'description' => $data->description,
                    'isActive' => (bool) $data->isActive,
                    'createdAt' => (string) $data->createdAt,
                ];
            }, $categories->items()),
            total: $categories->total(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            current_page: $categories->currentPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from(),
        ); */        
    }
}