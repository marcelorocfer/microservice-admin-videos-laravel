<?php

namespace Core\UseCase\Video;

use Core\Domain\Enum\MediaStatus;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Builder\Video\BuilderVideo;
use Core\Domain\Exceptions\NotFoundException;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

abstract class BaseVideoUseCase
{
    protected BuilderVideo $builder;

    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,

        protected CategoryRepositoryInterface $repositoryCategory,
        protected GenreRepositoryInterface $repositoryGenre,
        protected CastMemberRepositoryInterface $repositoryCastMember,
    ) {
        $this->builder = new BuilderVideo;
    }

    protected function storageFiles(object $input): void
    {
        $entity = $this->builder->getEntity();
        $path = $entity->id();

        if ($pathVideoFile = $this->storageFile($path, $input->videoFile)) {
            $this->builder->addMediaVideo($pathVideoFile, MediaStatus::PROCESSING);
            $this->eventManager->dispatch(new VideoCreatedEvent($entity));
        }

        if ($pathBannerFile = $this->storageFile($path, $input->bannerFile)) {
            $this->builder->addTrailer($pathBannerFile);
        }

        if ($pathThumbFile = $this->storageFile($path, $input->bannerFile)) {
            $this->builder->addThumb($pathThumbFile);
        }

        if ($pathThumbHalfFile = $this->storageFile($path, $input->thumbHalf)) {
            $this->builder->addThumbHalf($pathThumbHalfFile);
        }

        if ($pathBannerFile = $this->storageFile($path, $input->bannerFile)) {
            $this->builder->addBanner($pathBannerFile);
        }
    }

    protected function storageFile(string $path, ?array $media = null): ?string
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

    protected function validateIds(array $ids, $repository, string $singularLabel, ?string $pluralLabel = null)
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
}
