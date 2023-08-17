<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Enum\Rating;
use App\Models\Video as Model;
use Core\Domain\Entity\Entity;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Video as VideoEntity;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;

class VideoRepository implements VideoRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $entity): Entity
    {
        $entityDB = $this->model->create([
            'id' => $entity->id(),
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'rating' => $entity->rating->value,
            'duration' => $entity->duration,
            'opened' => $entity->opened,

        ]);

        $this->syncRelationships($entityDB, $entity);

        return $this->convertObjectToEntity($entityDB);
    }

    public function findById(string $id): Entity
    {

    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {

    }

    public function update(Entity $entity): Entity
    {

    }

    public function delete(string $id): bool
    {

    }

    public function updateMedia(Entity $entity): Entity
    {

    }

    protected function syncRelationships(Model $model, Entity $entity)
    {
        $model->categories()->sync($entity->categorieIds);
        $model->genres()->sync($entity->genreIds);
        $model->castMembers()->sync($entity->castMemberIds);
    }

    private function convertObjectToEntity(object $model): VideoEntity
    {
        $entity = new VideoEntity(
            id: new Uuid($model->id),
            title: $model->title,
            description: $model->description,
            yearLaunched: (int) $model->year_launched,
            rating: Rating::from($model->rating),
            duration: (bool) $model->duration,
            opened: $model->opened,
        );

        foreach ($model->categories as $category) {
            $entity->addCategoryId($category->id);
        }

        foreach ($model->genres as $genre) {
            $entity->addGenre($genre->id);
        }

        foreach ($model->castMembers as $castMember) {
            $entity->addCastMember($castMember->id);
        }

        return $entity;
    }
}
