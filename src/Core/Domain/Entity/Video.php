<?php

namespace Core\Domain\Entity;

use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Traits\MagicalMethodsTrait;

class Video
{
    use MagicalMethodsTrait;

    protected array $categorieIds = [];
    protected array $genreIds = [];
    protected array $castMemberIds = [];

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

    public function addCategoryId(string $categoryId)
    {
        array_push($this->categorieIds, $categoryId);
    }

    public function removeCategoryId(string $categoryId)
    {
        unset($this->categorieIds[array_search($categoryId, $this->categorieIds)]);
    }

    public function addGenre(string $genreId)
    {
        array_push($this->genreIds, $genreId);
    }

    public function removeGenre(string $genreId)
    {
        unset($this->genreIds[array_search($genreId, $this->genreIds)]);
    }

    public function addCastMember(string $castMemberId)
    {
        array_push($this->castMemberIds, $castMemberId);
    }

    public function removeCastMember(string $castMemberId)
    {
        unset($this->castMemberIds[array_search($castMemberId, $this->castMemberIds)]);
    }
}
