<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreRepository;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\ListGenreUseCase;
use Tests\TestCase;

class ListGenreUseCaseTest extends TestCase
{
    public function testFindById()
    {
        $useCase = new ListGenreUseCase(
            new GenreRepository(new Model())
        );

        $genre = Model::factory()->create();

        $response = $useCase->execute(new GenreInputDTO(
            id: $genre->id
        ));

        $this->assertEquals($genre->id, $response->id);
        $this->assertEquals($genre->name, $response->name);
    }
}
