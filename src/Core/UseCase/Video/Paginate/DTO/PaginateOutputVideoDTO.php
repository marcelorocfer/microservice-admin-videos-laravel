<?php

namespace Core\UseCase\Video\Paginate\DTO;

class PaginateOutputVideoDTO
{
    public function __construct(
        public string $filter = '',
        public string $order = 'DESC',
        public int $page = 1,
        public int $per_page = 15,
    ) {}
}