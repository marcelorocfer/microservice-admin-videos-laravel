<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\Category as Model;
use Illuminate\Foundation\Testing\WithFaker;
use App\Repositories\Eloquent\CategoryRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CategoryRepositoryTest extends TestCase
{
    public function testInsert()
    {
        $repository = new CategoryRepository(new Model());

        $entity = new EntityCategory(
            name: 'Test'
        );

        $response = $repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name
        ]);
    }
}
