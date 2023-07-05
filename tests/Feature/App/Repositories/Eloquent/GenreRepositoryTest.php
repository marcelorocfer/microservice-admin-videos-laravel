<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\Genre as Model;

class GenreRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreRepository(new Model());
    }  
    
}