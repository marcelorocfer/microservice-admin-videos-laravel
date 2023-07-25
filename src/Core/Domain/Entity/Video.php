<?php

namespace Core\Domain\Entity;

use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Traits\MagicalMethodsTrait;

class Video
{
    use MagicalMethodsTrait;

    protected array $categoriesId = [];
    protected array $genresId = [];

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
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategoryId(string $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }

    public function addGenre(string $genreId)
    {
        array_push($this->genresId, $genreId);
    }

    public function removeGenre(string $genreId)
    {
        unset($this->genresId[array_search($genreId, $this->genresId)]);
    }
}
