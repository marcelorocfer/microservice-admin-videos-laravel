<?php

namespace Core\UseCase\Video\Create;

use Throwable;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\ValueObject\Image;
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
    protected Entity $entity;

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
        $this->validateAllIds($input);
        $this->entity = $this->createEntity($input);

        try {
            $this->repository->insert($this->entity);

            $this->storageFiles($input);

            $this->repository->updateMedia($this->entity);

            $this->transaction->commit();

            return $this->output($this->entity);
        } catch (Throwable $th) {
            $this->transaction->rollback();
            // if (isset($pathMedia)) $this->storage->delete($pathMedia);
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

        // add categories_ids in entity
        foreach ($input->categories as $categoryId) {
            $entity->addCategoryId($categoryId);
        }

        // add genres_ids in entity
        foreach ($input->genres as $genreId) {
            $entity->addGenre($genreId);
        }

        // add cast_members_ids in entity
        foreach ($input->castMembers as $castMemberId) {
            $entity->addCastMember($castMemberId);
        }

        return $entity;
    }

    protected function storageFiles(object $input): void
    {
        if ($pathVideoFile = $this->storageFile($this->entity->id(), $input->videoFile)) {
            $media = new Media(
                filePath: $pathVideoFile,
                mediaStatus: MediaStatus::PROCESSING,
            );
            $this->entity->setVideoFile($media);
            $this->eventManager->dispatch(new VideoCreatedEvent($this->entity));
        }

        if ($pathBannerFile = $this->storageFile($this->entity->id(), $input->bannerFile)) {
            $this->entity->setTrailerFile(new Media(
                filePath: $pathBannerFile,
                mediaStatus: MediaStatus::PROCESSING,
            ));
        }

        if ($pathThumbFile = $this->storageFile($this->entity->id(), $input->bannerFile)) {
            $this->entity->setThumbFile(new Image(
                path: $pathThumbFile,
            ));
        }

        if ($pathThumbHalfFile = $this->storageFile($this->entity->id(), $input->thumbHalf)) {
            $this->entity->setThumbHalf(new Image(
                path: $pathThumbHalfFile,
            ));
        }

        if ($pathBannerFile = $this->storageFile($this->entity->id(), $input->bannerFile)) {
            $this->entity->setBannerHalf(new Image(
                path: $pathBannerFile,
            ));
        }
    }

    private function storageFile(string $path, ?array $media = null): ?string
    {
        if ($media) {
            return $this->storage->store(
                path: $path,
                file: $media,
            );
        }

        return null;
    }

    protected function validateAllIds(object $input)
    {
        $this->validateIds(
            ids: $input->categories,
            repository: $this->repositoryCategory,
            singularLabel: 'Category',
            pluralLabel: 'Categories',
        );

        $this->validateIds(
            ids: $input->genres,
            repository: $this->repositoryGenre,
            singularLabel: 'Genre',
        );

        $this->validateIds(
            ids: $input->castMembers,
            repository: $this->repositoryCastMember,
            singularLabel: 'Cast Member',
        );
    }

    private function validateIds(array $ids = [], $repository, string $singularLabel, ?string $pluralLabel = null)
    {
        $idsDB = $repository->getIdsListIds($ids);

        $arrayDiff = array_diff($ids, $idsDB);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? $pluralLabel ?? $singularLabel . 's' : $singularLabel,
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

    private function output(Entity $entity): CreateOutputVideoDTO
    {
        return new CreateOutputVideoDTO(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating,
            categories: $entity->categorieIds,
            genres: $entity->genreIds,
            castMembers: $entity->castMemberIds,
            videoFile: $entity->videoFile()?->filePath,
            trailerFile: $entity->trailerFile()?->filePath,
            thumbFile: $entity->thumbFile()?->path(),
            thumbHalf: $entity->thumbHalf()?->path(),
            bannerFile: $entity->bannerFile()?->path(),
        );
    }
}
