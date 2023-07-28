<?php

namespace Core\Domain\Entity;

use Core\Domain\Notification\Notification;

abstract class Entity
{
    protected $notification;

    public function __construct()
    {
        $this->notification = new Notification();
    }
    
    public function __get($property)
    {
        if (isset($this->{$property})) {
            return $this->{$property};
        }

        $className = get_class($this);
        throw new Exception("Property {$property} not found in {$className}");

    }

    public function id(): string
    {
        return (string) $this->id;
    }

    public function created_at(): string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }
}
