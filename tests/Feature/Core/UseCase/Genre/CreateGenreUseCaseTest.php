<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as GenreModel;
use App\Models\Category as CategoryModel;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\Domain\Exceptions\NotFoundException;
use App\Repositories\Transactions\DBTransaction;
use Core\UseCase\DTO\Genre\Create\GenreCreateInputDTO;
use App\Repositories\Eloquent\{CategoryRepository, GenreRepository};

class CreateGenreUseCaseTest extends TestCase
{
    public function testInsert()
    {
        $repository = new GenreRepository(new GenreModel());
        $repositoryCategory = new CategoryRepository(new CategoryModel());

        $useCase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = CategoryModel::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        $useCase->execute(
            new GenreCreateInputDTO(
                name: 'test',
                categoriesId: $categoriesIds
            )
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'test'
        ]); 

        $this->assertDatabaseCount('category_genre', 10);
    }

    public function testExceptionInsertGenreWithCategoriesIdsInvalid() 
    {
        $this->expectException(NotFoundException::class);

        $repository = new GenreRepository(new GenreModel());
        $repositoryCategory = new CategoryRepository(new CategoryModel());

        $useCase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = CategoryModel::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        array_push($categoriesIds, 'fake_id');

        $useCase->execute(
            new GenreCreateInputDTO(
                name: 'test',
                categoriesId: $categoriesIds
            )
        );
    }

    public function testTransactionsInsert()
    {
        $repository = new GenreRepository(new GenreModel());
        $repositoryCategory = new CategoryRepository(new CategoryModel());

        $useCase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = CategoryModel::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        try {
            $useCase->execute(
                new GenreCreateInputDTO(
                    name: 'test',
                    categoriesId: $categoriesIds
                )
            );

            $this->assertDatabaseHas('genres', [
                'name' => 'test'
            ]); 
    
            $this->assertDatabaseCount('category_genre', 10);
        } catch (\Throwable $th) {
            //throw $th;
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }
    }
}
