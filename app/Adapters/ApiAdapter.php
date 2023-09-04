<?php

namespace App\Adapters;

use Core\Domain\Repository\PaginationInterface;

class ApiAdapter
{
    public function __construct(
        private PaginationInterface $response
    ) {}

    public function toJson()
    {
        return DefaultResource::collection(collect($this->response->items()))
                                ->additional([
                                    'meta' => [
                                        'total' => $this->response->total(),
                                        'current_page' => $this->response->currentPage(),
                                        'last_page' => $this->response->lastPage(),
                                        'first_page' => $this->response->firstPage(),
                                        'per_page' => $this->response->perPage(),
                                        'to' => $this->response->to(),
                                        'from' => $this->response->from(),
                                    ]
                                ]);
    }
}
