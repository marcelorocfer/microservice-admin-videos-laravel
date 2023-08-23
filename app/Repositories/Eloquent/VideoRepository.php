<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Enum\Rating;
use App\Models\Video as Model;
use Core\Domain\Entity\Entity;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Video as VideoEntity;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use App\Repositories\Eloquent\Traits\VideoTrait;
use Core\Domain\Builder\Video\UpdateVideoBuilder;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Image as ValueObjectImage;
use Core\Domain\ValueObject\Media as ValueObjectMedia;

class VideoRepository implements VideoRepositoryInterface
{
    use VideoTrait;

    public function __construct(
        protected Model $model
    ) {}

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
        if (!$entityDB = $this->model->find($id)) {
            throw new NotFoundException('Video not found');
        }

        return $this->convertObjectToEntity($entityDB);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $result = $this->model
                        ->where(function ($query) use ($filter) {
                            if ($filter) {
                                $query->where('title', 'LIKE', "%{$filter}%");
                            }
                        })
                        ->orderBy('title', $order)
                        ->get();

        return $result->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
                        ->where(function ($query) use ($filter) {
                            if ($filter) {
                                $query->where('title', 'LIKE', "%{$filter}%");
                            }
                        })
                        ->orderBy('title', $order)
                        ->paginate($totalPage, ['*'], 'page', $page);

        return new PaginationPresenter($result);
    }

    public function update(Entity $entity): Entity
    {
        if (!$entityDB = $this->model->find($entity->id())) {
            throw new NotFoundException('Video not found');
        }

        $entityDB->update([
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'rating' => $entity->rating->value,
            'duration' => $entity->duration,
            'opened' => $entity->opened,
        ]);

        $entityDB->refresh();

        $this->syncRelationships($entityDB, $entity);

        return $this->convertObjectToEntity($entityDB);
    }

    public function delete(string $id): bool
    {
        if (!$entityDB = $this->model->find($id)) {
            throw new NotFoundException('Video not found');
        }

        return $entityDB->delete();
    }

    public function updateMedia(Entity $entity): Entity
    {
        if (!$objectModel = $this->model->find($entity->id())) {
            throw new NotFoundException('Video not found');
        }

        $this->updateMediaVideo($entity, $objectModel);
        $this->updateMediaTrailer($entity, $objectModel);
        $this->updateImageBanner($entity, $objectModel);
        $this->updateImageThumb($entity, $objectModel);
        $this->updateImageThumbHalf($entity, $objectModel);

        return $this->convertObjectToEntity($objectModel);
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

        $builder = (new UpdateVideoBuilder())
                    ->setEntity($entity);

        if ($trailer = $model->trailer) {
            $builder->addTrailer($trailer->file_path);
        }

        if ($mediaVideo = $model->media) {
            $builder->addMediaVideo(
                path: $mediaVideo->file_path,
                mediaStatus: MediaStatus::from($mediaVideo->media_status),
                encodedPath: $mediaVideo->encoded_path,
            );
        }

        if ($banner = $model->banner) {
            $builder->addBanner($banner->path);
        }

        if ($thumb = $model->thumb) {
            $builder->addThumb($thumb->path);
        }

        if ($thumbHalf = $model->thumbHalf) {
            $builder->addThumbHalf($thumbHalf->path);
        }

        return $builder->getEntity();
    }
}
