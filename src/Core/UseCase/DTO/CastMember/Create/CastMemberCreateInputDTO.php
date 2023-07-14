<?php

namespace Core\UseCase\DTO\CastMember\Create;

class CastMemberCreateInputDTO
{
    public function __construct(
        protected string $name,
        protected int $type,
    )
    {}
}
