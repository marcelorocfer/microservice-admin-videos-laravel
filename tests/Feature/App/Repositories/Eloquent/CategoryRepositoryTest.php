<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Throwable;
use Tests\TestCase;
use App\Models\Category as Model;
use Core\Domain\Exceptions\NotFoundException;
use App\Repositories\Eloquent\CategoryRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CategoryRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository(new Model());
    }  

    public function testInsert()
    {   
        $entity = new EntityCategory(
            name: 'Teste'
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name
        ]);
    }

    public function testFindById()
    {
        $category = Model::factory()->create();
        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($category->id, $response->id());
    }

    public function testFindByIdNotFound()
    {
        try {
            $this->repository->findById('fakeValue');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testFindAll()
    {
        $categories = Model::factory()->count(50)->create();
        $response = $this->repository->findAll();
        $this->assertEquals(count($response), count($categories));
    }
}
