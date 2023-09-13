<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Category as CategoryModel;
use App\Models\Genre as GenreModel;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\GenreRepository;
use App\Repositories\Transactions\DBTransaction;
use Core\Domain\Exceptions\NotFoundException;
use Core\UseCase\DTO\Genre\Update\GenreUpdateInputDTO;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Tests\TestCase;

class UpdateGenreUseCaseTest extends TestCase
{
    public function testUpdate()
    {
        $repository = new GenreRepository(new GenreModel());
        $repositoryCategory = new CategoryRepository(new CategoryModel());

        $useCase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = CategoryModel::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        $genre = GenreModel::factory()->create();

        $useCase->execute(
            new GenreUpdateInputDTO(
                id: $genre->id,
                name: 'New name',
                categoriesId: $categoriesIds
            )
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'New name',
        ]);

        $this->assertDatabaseCount('category_genre', 10);
    }

    public function testExceptionUpdateGenreWithCategoriesIdsInvalid()
    {
        $this->expectException(NotFoundException::class);

        $repository = new GenreRepository(new GenreModel());
        $repositoryCategory = new CategoryRepository(new CategoryModel());

        $useCase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = CategoryModel::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        array_push($categoriesIds, 'fake_id');

        $genre = GenreModel::factory()->create();

        $useCase->execute(
            new GenreUpdateInputDTO(
                id: $genre->id,
                name: 'New name',
                categoriesId: $categoriesIds
            )
        );
    }

    public function testTransactionsUpdate()
    {
        $repository = new GenreRepository(new GenreModel());
        $repositoryCategory = new CategoryRepository(new CategoryModel());

        $useCase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = CategoryModel::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        try {
            $genre = GenreModel::factory()->create();

            $useCase->execute(
                new GenreUpdateInputDTO(
                    id: $genre->id,
                    name: 'New name',
                    categoriesId: $categoriesIds
                )
            );

            $this->assertDatabaseHas('genres', [
                'name' => 'New name',
            ]);

            $this->assertDatabaseCount('category_genre', 10);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }
    }
}
