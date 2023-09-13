<?php

namespace Core\UseCase\Video\ChangeEncoded;

use Core\Domain\Enum\MediaStatus;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Media;
use Core\UseCase\Video\DTO\ChangeEncodedVideoDTO;
use Core\UseCase\Video\DTO\ChangeEncodedVideoOutputDTO;

class ChangeEncodedPathVideo
{
    public function __construct(
        protected VideoRepositoryInterface $repository
    ) {
    }

    public function exec(ChangeEncodedVideoDTO $input): ChangeEncodedVideoOutputDTO
    {
        $entity = $this->repository->findById($input->id);

        $entity->setVideoFile(
            new Media(
                filePath: $entity->videoFile()?->filePath ?? '',
                mediaStatus: MediaStatus::COMPLETED,
                encodedPath: $input->encodedPath
            )
        );

        $this->repository->updateMedia($entity);

        return new ChangeEncodedVideoOutputDTO(
            id: $entity->id(),
            encodedPath: $input->encodedPath,
        );
    }
}
