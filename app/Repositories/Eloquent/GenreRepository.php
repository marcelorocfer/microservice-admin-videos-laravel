<?php

namespace App\Repositories\Eloquent;

use DateTime;
use App\Models\Genre as Model;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Genre as Entity;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\GenreRepositoryInterface;

class GenreRepository implements GenreRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $genre): Entity
    {
        $genreDB = $this->model->create([
            'id' => $genre->id(),
            'name' => $genre->name,
            'is_active' => $genre->is_active,
            'created_at' => $genre->created_at(),
        ]);

        if (count($genre->categoriesId) > 0) {
            $genreDB->categories()->sync($genre->categoriesId);
        }

        return $this->toGenre($genreDB);
    }
    
    public function findById(string $id): Entity
    {
        if (!$genreDB = $this->model->find($id)) {
            throw new NotFoundException("Genre {$id} not found!");
        }

        return $this->toGenre($genreDB);
    }
    
    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }
    
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {

    }
    
    public function update(Entity $genre): Entity
    {

    }
    
    public function delete(string $id): bool
    {

    }    

    private function toGenre(Model $object): Entity
    {
        $entity = new Entity(
            id: new Uuid($object->id),
            name: $object->name,
            created_at: new DateTime($object->created_at),
        );
        ((bool) $object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }
}