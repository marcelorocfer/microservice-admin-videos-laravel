<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\CastMemberInputDTO;
use Core\UseCase\DTO\CastMember\Delete\DeleteCastMemberOutputDTO;

class DeleteCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberInputDTO $input): DeleteCastMemberOutputDTO
    {
        $hasDeleted = $this->repository->delete($input->id);

        return new DeleteCastMemberOutputDTO(
            success: $hasDeleted
        );
    }
}
