<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\DTO\Genre\Create\GenreCreateInputDTO;

class CreateGenreUseCaseUnitTest extends TestCase
{
    public function test_create()
    {
        $uuid = (string) Uuid::uuid4();

        $mockEntity = Mockery::mock(EntityGenre::class, [
            'test', new ValueObjectUuid($uuid), true, []
        ]);
        $mockEntity->shouldReceive('created_at')->andReturn(date('Y-m-d H:i:s'));
        
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')->andReturn($mockEntity);

        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);

        $mockCreateInputDTO = Mockery::mock(GenreCreateInputDTO::class, [
            'name', [$uuid], true
        ]);
        
        $useCase = new CreateGenreUseCase($mockRepository, $mockTransaction);
        $useCase->execute($mockCreateInputDTO);

        Mockery::close();
    }
}
