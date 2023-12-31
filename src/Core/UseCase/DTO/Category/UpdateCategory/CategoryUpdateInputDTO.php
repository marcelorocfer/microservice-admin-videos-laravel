<?php

namespace Core\UseCase\DTO\Category\UpdateCategory;

class CategoryUpdateInputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description = null,
        public bool $is_active = true
    ) {

    }
}
