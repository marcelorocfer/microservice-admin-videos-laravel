<?php

namespace Tests\Feature\Core\UseCase\Video;

use Tests\TestCase;
use App\Models\Video;
use Core\Domain\Exceptions\NotFoundException;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\DTO\ListInputVideoUseCase;

class ListVideoUseCaseTest extends TestCase
{
    public function test_list()
    {
        $video = Video::factory()->create();

        $useCase = new ListVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->exec(new ListInputVideoUseCase(
            id: $video->id
        ));

        $this->assertNotNull($response);
        $this->assertEquals($video->id, $response->id);
    }

    public function test_exception()
    {
        $this->expectException(NotFoundException::class);

        $useCase = new ListVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->exec(new ListInputVideoUseCase(
            id: 'fake_id'
        ));
    }
}
