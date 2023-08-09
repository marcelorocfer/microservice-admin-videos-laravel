<?php

namespace Core\UseCase\Video\Update;

use Core\Domain\Builder\Video\Builder;
use Core\UseCase\Video\BaseVideoUseCase;
use Core\Domain\Builder\Video\UpdateVideoBuilder;

class UpdateVideoUseCase extends BaseVideoUseCase
{
    protected function getBuilder(): Builder
    {
        return new UpdateVideoBuilder();
    }
}
