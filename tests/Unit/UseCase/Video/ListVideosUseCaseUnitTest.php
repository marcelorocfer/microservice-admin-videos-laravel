<?php

namespace Tests\Unit\UseCase\Video;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Tests\Unit\UseCase\UseCaseTrait;
use Core\UseCase\Video\Paginate\ListVideosUseCase;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\DTO\PaginateInputVideoDTO;
use Core\UseCase\Video\Paginate\DTO\PaginateOutputVideoDTO;

class ListVideosUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function test_list_paginate()
    {
        $useCase = new ListVideosUseCase(
            repository: $this->mockRepository()
        );

        $response = $useCase->exec(
            input: $this->mockInputDTO()
        );

        $this->assertInstanceOf(PaginateOutputVideoDTO::class, $response);

        Mockery::close();
    }

    private function mockRepository()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
                        ->once()
                        ->andReturn($this->mockPagination());
        return $mockRepository;
    }

    private function mockInputDTO()
    {
        return Mockery::mock(PaginateInputVideoDTO::class, [
            '',
            'DESC',
            1,
            15,
        ]);
    }
}
