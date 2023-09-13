<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Category;
use Core\Domain\Entity\Entity;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Model $category)
    {
        $this->model = $category;
    }

    public function insert(Entity $category): Entity
    {
        $category = $this->model->create([
            'id' => $category->id(),
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->is_active,
            'created_at' => $category->created_at(),
        ]);

        return $this->toCategory($category);
    }

    public function findById(string $id): Category
    {
        if (! $category = $this->model->find($id)) {
            throw new NotFoundException('Category Not Found');
        }

        return $this->toCategory($category);
    }

    public function getIdsListIds(array $categoriesId = []): array
    {
        return $this->model
            ->whereIn('id', $categoriesId)
            ->pluck('id')
            ->toArray();
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('id', $order)
            ->get();

        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query = $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginationPresenter($paginator);
    }

    public function update(Entity $category): Entity
    {
        if (! $categoryDB = $this->model->find($category->id())) {
            throw new NotFoundException('Category Not Found');
        }

        $categoryDB->update([
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->is_active,
        ]);

        $categoryDB->refresh();

        return $this->toCategory($categoryDB);
    }

    public function delete(string $id): bool
    {
        if (! $categoryDB = $this->model->find($id)) {
            throw new NotFoundException('Category Not Found');
        }

        return $categoryDB->delete();
    }

    private function toCategory(object $object): Category
    {
        $entity = new Category(
            id: $object->id,
            name: $object->name,
            description: $object->description,
        );
        ((bool) $object->is_active) ? $entity->activate() : $entity->disable();

        return $entity;
    }
}
