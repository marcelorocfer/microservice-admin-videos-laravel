<?php

namespace Tests\Unit\UseCase\Video;

use Mockery;
use stdClass;
use Core\Domain\Enum\Rating;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Video as Entity;
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

        $this->assertInstanceOf(ListOutputVideoUseCase::class, $response);
    }

    private function mockInputDTO(string $id)
    {
        return Mockery::mock(ListInputVideoUseCase::class, [
            $id
        ]);
    }

    private function mockRepository()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
                        ->once()
                        ->andReturn($this->getEntity());
        return $mockRepository;
    }

    private function getEntity(): Entity
    {
        return new Entity(
            title: 'title',
            description: 'description',
            yearLaunched: 2026,
            duration: 1,
            opened: true,
            rating: Rating::L,
        );
    }
}
