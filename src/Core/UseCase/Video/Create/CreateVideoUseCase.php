<?php

namespace Core\UseCase\Video\Create;

use Throwable;
use Core\Domain\Entity\Video as Entity;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\Create\DTO\CreateOutputVideoDTO;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,
    ) {}

    public function exec(CreateInputVideoDTO $input): CreateOutputVideoDTO
    {
        $entity = $this->createEntity($input);

        try {
            $this->repository->insert($entity);

            $this->transaction->commit();
        } catch (Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }

        return new CreateOutputVideoDTO();
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
}
