<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Genre as Model;
use Core\Domain\Entity\Genre as Entity;
use Core\Domain\Exceptions\NotFoundException;
use App\Repositories\Eloquent\GenreRepository;
use Core\Domain\Repository\GenreRepositoryInterface;

class GenreRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreRepository(new Model());
    }  

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    }
    
    public function testInsert()
    {
        $entity = new Entity(name: 'New genre');
        $response = $this->repository->insert($entity);

        $this->assertEquals($entity->id, $response->id);
        $this->assertEquals($entity->name, $response->name);
        $this->assertDatabaseHas('genres', [
            'id' => $entity->id()
        ]);
    }
    
    public function testInsertDeactivate()
    {
        $entity = new Entity(name: 'New genre');
        $entity->deactivate();

        $this->repository->insert($entity);
        
        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'is_active' => false
        ]);
    }

    public function testInsertWithRelationships()
    {
        $categories = Category::factory()->count(4)->create();
        $genre = new Entity(name: 'New genre');

        foreach ($categories as $category) {
            $genre->addCategory($category->id);
        }

        $response = $this->repository->insert($genre);

        $this->assertDatabaseHas('genres', [
            'id' => $response->id(),
        ]);

        $this->assertDatabaseCount('category_genre', 4);
    }

    public function testNotFoundById()
    {
        $this->expectException(NotFoundException::class);

        $genre = 'fake_value';

        $this->repository->findById($genre);
    }

    public function testFindById()
    {
        $genre = Model::factory()->create();

        $response = $this->repository->findById($genre->id);

        $this->assertEquals($genre->id, $response->id());
        $this->assertEquals($genre->name, $response->name);
    }
}