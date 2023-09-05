<?php

namespace Core\UseCase\Video\DTO;

class ChangeEncodedVideoOutputDTO
{
    public function __construct(
        public string $id,
        public string $encodedPath,
    ) {}
}
