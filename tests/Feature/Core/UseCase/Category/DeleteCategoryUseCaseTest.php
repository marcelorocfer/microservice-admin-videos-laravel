<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as Model;
use Illuminate\Foundation\Testing\WithFaker;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\Category\DeleteCategoryUseCase;
use App\Repositories\Eloquent\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function test_delete()
    {
        $categoryFactory = Model::factory()->create();

        $repository = new CategoryRepository(new Model());
        $useCase = new DeleteCategoryUseCase($repository);
        $useCase->execute(
            new CategoryInputDTO(
                id: $categoryFactory->id
            )
        );

        $this->assertSoftDeleted($categoryFactory);
    }
}
