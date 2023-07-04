<?php

namespace Core\UseCase\DTO\Genre\Update;

class GenreUpdateInputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public array $categoriesId = [],
    ) {}
}