<?php

namespace Core\Domain\Notification;

class Notification
{
    private $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $error [context, message]
     * @return void
     */
    public function addError(array $error): void
    {
        array_push($this->errors, $error);
    }
}
