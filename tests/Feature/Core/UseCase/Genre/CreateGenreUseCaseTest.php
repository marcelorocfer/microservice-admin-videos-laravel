<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as GenreModel;
use App\Models\Category as CategoryModel;
use Core\UseCase\Genre\CreateGenreUseCase;
use App\Repositories\Transactions\DBTransaction;
use Core\UseCase\DTO\Genre\Create\GenreCreateInputDTO;
use App\Repositories\Eloquent\{CategoryRepository, GenreRepository};

class CreateGenreUseCaseTest extends TestCase
{
    public function test_insert()
    {
        $repository = new GenreRepository(new GenreModel());
        $repositoryCategory = new CategoryRepository(new CategoryModel());
        $useCase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $response = $useCase->execute(
            new GenreCreateInputDTO(
                name: 'test'
            )
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'test'
        ]);
    }
}
