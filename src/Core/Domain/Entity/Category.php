<?php

namespace Core\Domain\Entity;

use DateTime;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\Entity\Traits\MagicalMethodsTrait;

class Category
{
    use MagicalMethodsTrait;

    public function __construct(
        protected Uuid | string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $isActive = true,
        protected DateTime | string $createdAt = '',
    ){
        $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
        $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime();

        $this->validate();
    }

    public function activate(): void
    {   
        $this->isActive = true;
    }

    public function deactivate(): void
    {   
        $this->isActive = false;
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
