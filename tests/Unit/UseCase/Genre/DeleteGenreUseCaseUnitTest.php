<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\Delete\DeleteGenreOutputDTO;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class DeleteGenreUseCaseUnitTest extends TestCase
{
    public function test_delete()
    {
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
            ->once()
            ->with($uuid)
            ->andReturn(true);

        $mockInputDTO = Mockery::mock(GenreInputDTO::class, [$uuid]);

        $useCase = new DeleteGenreUseCase($mockRepository);
        $response = $useCase->execute($mockInputDTO);

        $this->assertInstanceOf(DeleteGenreOutputDTO::class, $response);
        $this->assertTrue($response->success);
    }

    public function test_delete_fail()
    {
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
            ->times(1)
            ->with($uuid)
            ->andReturn(false);

        $mockInputDTO = Mockery::mock(GenreInputDTO::class, [$uuid]);

        $useCase = new DeleteGenreUseCase($mockRepository);
        $response = $useCase->execute($mockInputDTO);

        $this->assertFalse($response->success);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
