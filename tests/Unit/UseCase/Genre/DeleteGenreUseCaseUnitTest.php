<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\Delete\DeleteGenreOutputDTO;

class DeleteGenreUseCaseUnitTest extends TestCase
{
    public function test_delete()
    {
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')->andReturn(true);

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
        $mockRepository->shouldReceive('delete')->andReturn(false);

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
