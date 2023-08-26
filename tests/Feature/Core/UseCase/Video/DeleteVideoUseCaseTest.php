<?php

namespace Tests\Feature\Core\UseCase\Video;

use Tests\TestCase;
use App\Models\Video;
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Delete\DTO\DeleteInputVideoDTO;

class DeleteVideoUseCaseTest extends TestCase
{
    public function test_delete()
    {
        $video = Video::factory()->create();

        $useCase = new DeleteVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->exec(new DeleteInputVideoDTO(
            id: $video->id
        ));

        $this->assertTrue($response->deleted);
    }
}
