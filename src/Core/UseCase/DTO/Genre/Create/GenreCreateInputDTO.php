<?php

namespace Core\UseCase\DTO\Genre\Create;

class GenreCreateInputDTO
{
    public function __construct(
        public string $name,
        public array $categoriesId = [],
        public bool $is_active = true,
    ) {
    }
}
