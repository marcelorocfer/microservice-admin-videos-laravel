<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Entity\Category;
use App\Models\Category as Model;
use Core\Domain\Repository\PaginationInterface;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Model $category)
    {
        $this->model = $category;
    }

    public function insert(Category $category): Category
    {
        $category = $this->model->create([
            'id' => $category->id(),
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
            'created_at' => $category->createdAt(),
        ]);

        return $this->toCategory($category);
    }

    public function findById(string $id): Category
    {
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        return new PaginationPresenter();
    }

    public function update(Category $category): Category
    {
    }

    public function delete(string $id): bool
    {
    }

    private function toCategory(object $object): Category
    {
        return new Category(
            name: $object->name,
        );
    }
}
