<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as Model;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use App\Repositories\Eloquent\CategoryRepository;

class ListCategoryUseCaseTest extends TestCase
{
    public function test_list()
    {
        $categoryFactory = Model::factory()->create();

        $repository = new CategoryRepository(new Model());
        $useCase = new ListCategoryUseCase($repository);
        $response = $useCase->execute(
            new CategoryInputDTO(
                id: $categoryFactory->id
            )
        );

        $this->assertEquals($categoryFactory->id, $response->id);
        $this->assertEquals($categoryFactory->name, $response->name);
        $this->assertEquals($categoryFactory->description, $response->description);
    }
}
