<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreRepository;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Tests\TestCase;

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
            'id' => $genre->id,
        ]);
    }
}
