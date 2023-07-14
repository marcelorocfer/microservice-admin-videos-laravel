<?php

namespace Core\UseCase\DTO\CastMember\Create;

class CastMemberCreateOutputDTO
{
    public function __construct(
        protected string $id,
        protected string $name,
        protected int $type,
        protected string $created_at,
    )
    {}
}
