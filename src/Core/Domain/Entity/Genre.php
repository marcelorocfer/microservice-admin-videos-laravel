<?php

namespace Core\Domain\Entity;

use DateTime;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Traits\MagicalMethodsTrait;

class Genre 
{
    use MagicalMethodsTrait;

    public function __construct(
        protected string $name,
        protected ?Uuid $id = null,
        protected $is_active = true,
        protected ?DateTime $created_at = null,
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->created_at = $this->created_at ?? new DateTime();
    }
}