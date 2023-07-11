<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as Model;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\DeleteGenreUseCase;
use App\Repositories\Eloquent\GenreRepository;

class DeleteGenreUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $useCase = new DeleteGenreUseCase(
            new GenreRepository(new Model())
        );

        $genre = Model::factory()->create();

        $response = $useCase->execute(new GenreInputDTO(
            id: $genre->id
        ));

        $this->assertTrue($response->success);
        $this->assertSoftDeleted('genres', [
            'id' => $genre->id
        ]);
    }
}
