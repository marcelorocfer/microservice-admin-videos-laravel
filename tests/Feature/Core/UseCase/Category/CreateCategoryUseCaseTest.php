<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryRepository;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDTO;
use Tests\TestCase;

class CreateCategoryUseCaseTest extends TestCase
{
    public function test_create()
    {
        $repository = new CategoryRepository(new Model());
        $useCase = new CreateCategoryUseCase($repository);
        $response = $useCase->execute(
            new CategoryCreateInputDTO(
                name: 'Teste'
            )
        );

        $this->assertEquals('Teste', $response->name);
        $this->assertNotEmpty($response->id);
        $this->assertDatabaseHas('categories', [
            'id' => $response->id,
        ]);
    }
}
