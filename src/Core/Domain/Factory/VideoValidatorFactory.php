<?php

namespace Core\Domain\Factory;

use Core\Domain\Validation\ValidatorInterface;
use Core\Domain\Validation\VideoLaravelValidation;
use Core\Domain\Validation\VideoRakitValidator;

class VideoValidatorFactory
{
    public static function create(): ValidatorInterface
    {
        // return new VideoLaravelValidation();
        return new VideoRakitValidator();
    }
}
