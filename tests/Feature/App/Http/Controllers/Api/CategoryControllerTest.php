<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Category;
use App\Http\Controllers\Api\CategoryController;
use App\Repositories\Eloquent\CategoryRepository;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\{
    Request,
    Response,
    JsonResponse,
};
use App\Http\Requests\{
    StoreCategoryRequest,
    UpdateCategoryRequest,
};
use Core\UseCase\Category\{
    ListCategoryUseCase,
    ListCategoriesUseCase,
    CreateCategoryUseCase,
    DeleteCategoryUseCase,
    UpdateCategoryUseCase,
};

class CategoryControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        $this->repository = new CategoryRepository(new Category());
        $this->controller = new CategoryController();
        parent::setUp();
    }

    public function test_index()
    {
        $useCase = new ListCategoriesUseCase($this->repository);       
        $response = $this->controller->index(new Request(), $useCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function test_store()
    {
        $useCase = new CreateCategoryUseCase($this->repository);

        $request = new StoreCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Teste'
        ]));

        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }

    public function test_show()
    {
        $category = Category::factory()->create();

        $response = $this->controller->show(
            useCase: new ListCategoryUseCase($this->repository),
            id: $category->id,
        );
        
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    public function test_update()
    {
        $category = Category::factory()->create();

        $request = new UpdateCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Updated'
        ]));

        $response = $this->controller->update(
            request: $request,
            useCase: new UpdateCategoryUseCase($this->repository),
            id: $category->id
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertDatabaseHas('categories', [
            'name' => 'Updated'
        ]);
    }

    public function test_delete()
    {
        $category = Category::factory()->create();

        $response = $this->controller->destroy(
            useCase: new DeleteCategoryUseCase($this->repository),
            id: $category->id
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->status());
    }
}
