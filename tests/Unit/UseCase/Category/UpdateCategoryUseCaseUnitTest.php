<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateOutputDTO;

class UpdateCategoryUseCaseUnitTest extends TestCase 
{
    public function testRenameCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $categoryName = 'Name';
        $categoryDescription = 'Description';

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName,
            $categoryDescription
        ]);

        $this->mockEntity->shouldReceive('update');
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->mockRepository->shouldReceive('update')->andReturn($this->mockEntity);

        $this->mockInputDTO = Mockery::mock(CategoryUpdateInputDTO::class, [
            $uuid,
            'New name',
        ]);

        $useCase = new UpdateCategoryUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDTO);

        $this->assertInstanceOf(CategoryUpdateOutputDTO::class, $response);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->spy->shouldReceive('update')->andReturn($this->mockEntity);
        $useCase = new UpdateCategoryUseCase($this->spy);
        $useCase->execute($this->mockInputDTO);
        $this->spy->shouldHaveReceived('findById');
        $this->spy->shouldHaveReceived('update');

        Mockery::close();
    }
}