<?php

namespace Core\UseCase\DTO\CastMember\Create;

class CastMemberCreateInputDTO
{
    public function __construct(
        public string $name,
        public int $type,
    ) {
    }
}
