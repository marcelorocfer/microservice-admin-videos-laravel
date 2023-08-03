<?php

namespace Core\UseCase\Video\Create;

use Throwable;
use Core\Domain\Entity\Video as Entity;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Exceptions\NotFoundException;
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
        // create entity -> input
        $entity = new Entity(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: true,
            rating: $input->rating,
        );

        // add categories_ids in entity - validate
        $this->validateCategoriesId($input->categories);
        foreach ($input->categories as $categoryId) {
            $entity->addCategoryId($categoryId);
        }

        // add genres_ids in entity - validate
        $this->validateGenresId($input->genres);
        foreach ($input->genres as $genreId) {
            $entity->addGenre($genreId);
        }

        // add cast_members_ids in entity - validate
        $this->validateCastMembersId($input->castMembers);
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

    private function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDB = $this->repositoryCategory->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDB);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function validateGenresId(array $genresId = [])
    {
        $genresDB = $this->repositoryGenre->getIdsListIds($genresId);

        $arrayDiff = array_diff($genresId, $genresDB);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Genres' : 'Genre',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function validateCastMembersId(array $castMembersId = [])
    {
        $castMembersDB = $this->repositoryCastMember->getIdsListIds($castMembersId);

        $arrayDiff = array_diff($castMembersId, $castMembersDB);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'CastMembers' : 'CastMember',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }
}
