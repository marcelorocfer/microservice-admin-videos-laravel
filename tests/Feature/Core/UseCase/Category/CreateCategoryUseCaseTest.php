<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as Model;
use Core\UseCase\Category\CreateCategoryUseCase;
use App\Repositories\Eloquent\CategoryRepository;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDTO;

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
            'id' => $response->id
        ]);
    }
}
