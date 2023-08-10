<?php

namespace Tests\Unit\UseCase\Video;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\Domain\Repository\VideoRepositoryInterface;

class ListVideoUseCaseUnitTest extends TestCase
{
    public function test_list()
    {
        $useCase = new ListVideoUseCase(
            repository: $this->mockRepository()
        );

        $this->assertTrue(true);
    }

    private function mockRepository()
    {
        return Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
    }
}
