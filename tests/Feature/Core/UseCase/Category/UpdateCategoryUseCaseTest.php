<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryRepository;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{
    public function test_example()
    {
        $categoryFactory = Model::factory()->create();

        $repository = new CategoryRepository(new Model());
        $useCase = new UpdateCategoryUseCase($repository);
        $response = $useCase->execute(
            new CategoryUpdateInputDTO(
                id: $categoryFactory->id,
                name: 'Name updated'
            )
        );

        $this->assertEquals('Name updated', $response->name);
        $this->assertEquals($categoryFactory->description, $response->description);
        $this->assertDatabaseHas('categories', [
            'name' => $response->name,
        ]);
    }
}
