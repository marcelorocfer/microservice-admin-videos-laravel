<?php

namespace Tests\Unit\UseCase\Video;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\DTO\ListInputVideoUseCase;
use Core\UseCase\Video\List\DTO\ListOutputVideoUseCase;

class ListVideoUseCaseUnitTest extends TestCase
{
    public function test_list()
    {
        $uuid = Uuid::random();

        $useCase = new ListVideoUseCase(
            repository: $this->mockRepository()
        );

        $response = $useCase->exec(
            input: $this->mockInputDTO($uuid)
        );

        $this->assertInstanceOf(ListInputVideoUseCase::class, $response);
    }

    private function mockInputDTO(string $id)
    {
        return Mockery::mock(ListOutputVideoUseCase::class, [$id]);
    }

    private function mockRepository()
    {
        return Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
    }
}
