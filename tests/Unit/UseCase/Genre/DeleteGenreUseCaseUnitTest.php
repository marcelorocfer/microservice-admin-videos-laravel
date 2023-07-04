<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\DTO\Genre\Delete\DeleteGenreOutputDTO;

class DeleteGenreUseCaseUnitTest extends TestCase
{
    public function test_delete()
    {
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')->andReturn($this->mockEntity($uuid));
        
        $useCase = new DeleteGenreUseCase($mockRepository);

        Mockery::close();
    }

    private function mockEntity(string $uuid) 
    {
        $mockEntity = Mockery::mock(EntityGenre::class, [
            'test', new ValueObjectUuid($uuid), true, []
        ]);
        $mockEntity->shouldReceive('created_at')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update');
        $mockEntity->shouldReceive('addCategory');

        return $mockEntity;
    }
}
