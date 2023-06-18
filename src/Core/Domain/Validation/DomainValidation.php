<?php

namespace Core\Domain\Validation;

use Core\Domain\Exceptions\EntityValidationException;

class DomainValidation
{
    public static function notNull(string $value, string $message = null)
    {
        if (empty($value)) {
            throw new EntityValidationException($message ?? "Should not be empty or null!");
        }
    }

    public static function strMaxLength(string $value, int $length = 255, string $message = null)
    {
        if (strlen($value) >= $length) {
            throw new EntityValidationException($message ?? "The value must not be greater than {$length} characters!");
        }
    }

    public static function strMinLength(string $value, int $length = 3, string $message = null)
    {
        if (strlen($value) < $length) {
            throw new EntityValidationException($message ?? "The value must at least {$length} characters!");
        }
    }

    public static function strCanNullAndMaxLength(string $value = '', int $length = 255, string $message = null)
    {
        if (!empty($value) && strlen($value) > $length) {
            throw new EntityValidationException($message ?? "The value must not be greater than {$length} characters!");
        }
    }
}