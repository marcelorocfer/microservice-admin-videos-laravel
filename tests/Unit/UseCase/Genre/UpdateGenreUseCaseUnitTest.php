<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exceptions\NotFoundException;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\DTO\Genre\Update\{
    GenreUpdateInputDTO, 
    GenreUpdateOutputDTO
};
use Core\Domain\Repository\{
    CategoryRepositoryInterface, 
    GenreRepositoryInterface
};

class UpdateGenreUseCaseUnitTest extends TestCase
{
    public function test_update()
    {
        $uuid = (string) Uuid::uuid4();
        
        $useCase = new UpdateGenreUseCase($this->mockRepository($uuid), $this->mockTransaction(), $this->mockCategoryRepository($uuid));
        $response = $useCase->execute($this->mockUpdateInputDTO([$uuid]));

        $this->assertInstanceOf(GenreUpdateOutputDTO::class, $response);
    }

    public function test_update_categories_notfound()
    {
        $this->expectException(NotFoundException::class);

        $uuid = (string) Uuid::uuid4();
        
        $useCase = new UpdateGenreUseCase($this->mockRepository($uuid), $this->mockTransaction(), $this->mockCategoryRepository($uuid));
        $useCase->execute($this->mockUpdateInputDTO([$uuid, 'fake_value']));
    }

    private function mockEntity(string $uuid) 
    {
        $mockEntity = Mockery::mock(EntityGenre::class, [
            'test', new ValueObjectUuid($uuid), true, []
        ]);
        $mockEntity->shouldReceive('created_at')->andReturn(date('Y-m-d H:i:s'));

        return $mockEntity;
    }

    private function mockRepository(string $uuid)
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')->andReturn($this->mockEntity($uuid));

        return $mockRepository;
    }

    private function mockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        return $mockTransaction;
    }
    
    private function mockCategoryRepository(string $uuid)
    {
        $mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockCategoryRepository->shouldReceive('getIdsListIds')->andReturn([$uuid]);

        return $mockCategoryRepository;
    }

    private function mockUpdateInputDTO(array $categoriesIds)
    {
        return Mockery::mock(GenreUpdateInputDTO::class, [
            'name', $categoriesIds, true
        ]);
    }
}
