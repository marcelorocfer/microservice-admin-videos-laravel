<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Create\{
    CastMemberCreateInputDTO,
    CastMemberCreateOutputDTO
};

class CreateCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberCreateInputDTO $input): CastMemberCreateOutputDTO
    {
        return new CastMemberCreateOutputDTO(
            
        );
    }
}
