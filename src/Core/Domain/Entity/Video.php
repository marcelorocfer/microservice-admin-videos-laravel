<?php

namespace Core\Domain\Entity;

use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Traits\MagicalMethodsTrait;

class Video
{
    use MagicalMethodsTrait;

    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Uuid $id = null,
        protected bool $published = false,
    ) {
        $this->id = $this->id ?? Uuid::random();
    }
}
