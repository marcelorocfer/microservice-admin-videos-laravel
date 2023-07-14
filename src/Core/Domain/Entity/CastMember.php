<?php

namespace Core\Domain\Entity;

use DateTime;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Enum\CastMemberType;

class CastMember
{
    public function __construct(
        protected ?Uuid $id = null,
        protected string $name,
        protected CastMemberType $type,
        protected ?DateTime $created_at = null,

    ){
        $this->id = $this->id ?? Uuid::random();
        $this->created_at = $this->created_at ?? new DateTime();
    }
}
