<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use DateTime;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Genre as Model;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Genre as Entity;
use Core\Domain\Exceptions\NotFoundException;
use App\Repositories\Eloquent\GenreRepository;
use Core\Domain\Repository\GenreRepositoryInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;

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

    public function testFindAll()
    {
        $genres = Model::factory()->count(10)->create();

        $genresDB = $this->repository->findAll();

        $this->assertEquals(count($genres), count($genresDB));
    }

    public function testFindAllEmpty()
    {
        $genresDB = $this->repository->findAll();

        $this->assertCount(0, $genresDB);
    }

    public function testFindAllWithFilter()
    {
        Model::factory()->count(10)->create([
            'name' => 'Test'
        ]);
        Model::factory()->count(10)->create();

        $genresDB = $this->repository->findAll(
            filter: 'Test'
        );
        $this->assertEquals(10, count($genresDB));

        $genresDB = $this->repository->findAll();
        $this->assertEquals(20, count($genresDB));
    }

    public function testPagination()
    {
        Model::factory()->count(60)->create();

        $response = $this->repository->paginate();
        
        $this->assertEquals(15, count($response->items()));
        $this->assertEquals(60, $response->total()); 
    }

    public function testPaginationEmpty()
    {
        $response = $this->repository->paginate();
        
        $this->assertCount(0, $response->items());
        $this->assertEquals(0, $response->total()); 
    }

    public function testUpdate()
    {
        $genre = Model::factory()->create();

        $entity = new Entity(
            id: new Uuid($genre->id),
            name: $genre->name,
            is_active: (bool) $genre->is_active,
            created_at: new DateTime($genre->created_at),
        );

        $entity->update(
            name: 'Name updated',
        );

        $response = $this->repository->update($entity);

        $this->assertEquals('Name updated', $response->name);
        $this->assertDatabaseHas('genres', [
            'name' => 'Name updated'
        ]);
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundException::class);

        $genreId = (string) RamseyUuid::uuid4();

        $entity = new Entity(
            id: new Uuid($genreId),
            name: 'name',
            is_active: true,
            created_at: new DateTime(date('Y-m-d H:i:s'))
        );

        $entity->update(
            name: 'Name updated',
        );

        $this->repository->update($entity);
    }
}