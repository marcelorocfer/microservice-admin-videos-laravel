<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\ListGenreUseCase;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\DTO\Genre\{GenreInputDTO, GenreOutputDTO};

class ListGenreUseCaseUnitTest extends TestCase
{
    public function test_list_single()
    {
        $uuid = (string) Uuid::uuid4();

        $mockEntity = Mockery::mock(EntityGenre::class, [
            'test', new ValueObjectUuid($uuid), true, []
        ]);
        $mockEntity->shouldReceive('created_at')->andReturn(date('Y-m-d H:i:s'));
        
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')->andReturn($mockEntity);

        $mockInputDTO = Mockery::mock(GenreInputDTO::class, [
            $uuid
        ]);
        
        $useCase = new ListGenreUseCase($mockRepository);
        $response = $useCase->execute($mockInputDTO);

        $this->assertInstanceOf(GenreOutputDTO::class, $response);

        Mockery::close();
    }
}
