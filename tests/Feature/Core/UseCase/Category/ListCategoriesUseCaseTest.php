<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as Model;
use Illuminate\Foundation\Testing\WithFaker;
use Core\UseCase\Category\ListCategoriesUseCase;
use App\Repositories\Eloquent\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDTO;

class ListCategoriesUseCaseTest extends TestCase
{
    public function test_list_all()
    {
        $categoriesFactory = Model::factory()->count(20)->create();
        $response = $this->createUseCase();
        $this->assertCount(15, $response->items);
        $this->assertEquals(count($categoriesFactory), $response->total);
    }

    public function test_list_empty()
    {
        $response = $this->createUseCase();
        $this->assertCount(0, $response->items);
    }

    private function createUseCase()
    {
        $repository = new CategoryRepository(new Model());
        $useCase = new ListCategoriesUseCase($repository);
        return $useCase->execute(new ListCategoriesInputDTO());
    }
}
