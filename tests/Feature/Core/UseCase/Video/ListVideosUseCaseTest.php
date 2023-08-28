<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Tests\TestCase;
use Core\UseCase\Video\Paginate\ListVideosUseCase;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\DTO\PaginateInputVideoDTO;

class ListVideosUseCaseTest extends TestCase
{
    public function test_pagination()
    {
        $video = Video::factory()->count(30)->create();

        $useCase = new ListVideosUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->exec(new PaginateInputVideoDTO(
            filter: '',
            order: 'DESC',
            page: 1,
            per_page: 10
        ));

        $this->assertCount(10, $response->items);
        $this->assertEquals(30, $response->total);
    }
}
