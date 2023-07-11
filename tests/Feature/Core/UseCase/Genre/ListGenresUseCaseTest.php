<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre;
use Core\UseCase\Genre\ListGenresUseCase;
use App\Repositories\Eloquent\GenreRepository;
use Core\UseCase\DTO\Genre\ListGenres\ListGenresInputDTO;

class ListGenresUseCaseTest extends TestCase
{
    public function testfindAll()
    {
        $useCase = new ListGenresUseCase(
            new GenreRepository(new Genre())
        );

        $genre = Genre::factory()->count(100)->create();

        $response = $useCase->execute(
            new ListGenresInputDTO()
        );

        $this->assertEquals(15, count($response->items));
        $this->assertEquals(100, $response->total);
    }
}
