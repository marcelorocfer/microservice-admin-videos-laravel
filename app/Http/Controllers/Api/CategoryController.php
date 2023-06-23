<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDTO;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDTO;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Illuminate\Http\{
    Request,
    Response
};
use App\Http\Requests\{
    StoreCategoryRequest,
    UpdateCategoryRequest
};
use Core\UseCase\Category\{
    ListCategoryUseCase,
    CreateCategoryUseCase,
    ListCategoriesUseCase,
    UpdateCategoryUseCase,
};

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListCategoriesInputDTO(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15),
            )
        );

        return CategoryResource::collection(collect($response->items))
                                ->additional([
                                    'meta' => [
                                        'total' => $response->total,
                                        'last_page' => $response->last_page,
                                        'first_page' => $response->first_page,
                                        'per_page' => $response->per_page,
                                        'to' => $response->to,
                                        'from' => $response->from,
                                    ]
                                ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new CategoryCreateInputDTO(
                name: $request->name,
                description: $request->description ?? '',
                isActive: (bool) $request->is_active ?? true,
            )
        );

        return (new CategoryResource(collect($response)))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCategoryUseCase $useCase, $id)
    {
        $category = $useCase->execute(new CategoryInputDTO($id));

        return (new CategoryResource(collect($category)))->response();
    }

    public function update(UpdateCategoryRequest $request, UpdateCategoryUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new CategoryUpdateInputDTO(
                id: $id,
                name: $request->name,
            )
        );

        return (new CategoryResource(collect($response)))->response();
    }
}
