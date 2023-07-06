<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\Genre as Model;
use Core\Domain\Entity\Genre as Entity;
use App\Repositories\Eloquent\GenreRepository;

class GenreRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreRepository(new Model());
    }  
    
    public function testInsert()
    {
        $entity = new Entity(name: 'New genre');

        $response = $this->repository->insert($entity);

        dump($response);
    }
}