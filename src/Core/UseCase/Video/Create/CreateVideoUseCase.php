<?php

namespace Core\UseCase\Video\Create;

use Throwable;
use Core\Domain\Entity\Video as Entity;
use Core\Domain\Events\VideoCreatedEvent;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\Create\DTO\CreateOutputVideoDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,

        protected CategoryRepositoryInterface $repositoryCategory,
        protected GenreRepositoryInterface $repositoryGenre,
        protected CastMemberRepositoryInterface $repositoryCastMember,
    ) {}

    public function exec(CreateInputVideoDTO $input): CreateOutputVideoDTO
    {
        $entity = $this->createEntity($input);

        try {
            $this->repository->insert($entity);

            if ($pathMedia = $this->storeMedia($entity->id(), $input->videoFile)) {
                $this->eventManager->dispatch(new VideoCreatedEvent($entity));
            }

            $this->transaction->commit();

            return new CreateOutputVideoDTO();
        } catch (Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }

    }

    private function createEntity(CreateInputVideoDTO $input): Entity
    {
        $entity = new Entity(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: true,
            rating: $input->rating,
        );

        foreach ($input->categories as $categoryId) {
            $entity->addCategoryId($categoryId);
        }

        foreach ($input->genres as $genreId) {
            $entity->addGenre($genreId);
        }

        foreach ($input->castMembers as $castMemberId) {
            $entity->addCastMember($castMemberId);
        }

        return $entity;
    }

    private function storeMedia(string $path, ?array $media = null): string
    {
        if ($media) {
            return $this->storage->store(
                path: $path,
                file: $media,
            );
        }

        return '';
    }
}
