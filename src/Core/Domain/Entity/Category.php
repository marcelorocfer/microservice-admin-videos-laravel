<?php

namespace Core\Domain\Entity;

use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Category extends Entity
{
    public function __construct(
        protected Uuid|string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $is_active = true,
        protected DateTime|string $created_at = '',
    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
        $this->created_at = $this->created_at ? new DateTime($this->created_at) : new DateTime();

        $this->validate();
    }

    public function activate(): void
    {
        $this->is_active = true;
    }

    public function disable(): void
    {
        $this->is_active = false;
    }

    public function update(string $name, string $description = '')
    {
        $this->name = $name;
        $this->description = $description;

        $this->validate();
    }

    private function validate()
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
        DomainValidation::strCanNullAndMaxLength($this->description);
    }
}
