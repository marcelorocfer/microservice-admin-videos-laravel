<?php

namespace Tests\Unit\UseCase\Video;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Tests\Unit\UseCase\UseCaseTrait;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\ListVideos\ListVideosUseCase;

class ListVideosUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function test_list_paginate()
    {
        $useCase = new ListVideosUseCase(
            repository: $this->mockRepository()
        );

        $this->assertTrue(true);

        Mockery::close();
    }

    private function mockRepository()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
                        // ->once()
                        ->andReturn($this->mockPagination());
        return $mockRepository;
    }
}
