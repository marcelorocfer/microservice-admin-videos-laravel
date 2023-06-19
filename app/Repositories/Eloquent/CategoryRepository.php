<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Entity\Category;
use App\Models\Category as Model;
use Core\Domain\Exceptions\NotFoundException;
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
        if (!$category = $this->model->find($id)) {
            throw new NotFoundException('Category Not Found');
        }

        return $this->toCategory($category);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model
                            ->where(function($query) use ($filter) {
                                if ($filter) 
                                    $query->where('name', 'LIKE', "%{$filter}%");
                            })
                            ->orderBy('id', $order)
                            ->get();
        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginationPresenter($paginator);
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
            id: $object->id,
            name: $object->name,
            description: $object->description,
        );
    }
}
