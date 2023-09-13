<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MagicalMethodsTrait;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Genre
{
    use MagicalMethodsTrait;

    public function __construct(
        protected string $name,
        protected ?Uuid $id = null,
        protected $is_active = true,
        protected array $categoriesId = [],
        protected ?DateTime $created_at = null,
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->created_at = $this->created_at ?? new DateTime();

        $this->validate();
    }

    public function activate()
    {
        $this->is_active = true;
    }

    public function deactivate()
    {
        $this->is_active = false;
    }

    public function update(string $name)
    {
        $this->name = $name;

        $this->validate();
    }

    public function addCategory(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategory(string $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }

    private function validate()
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
    }
}
