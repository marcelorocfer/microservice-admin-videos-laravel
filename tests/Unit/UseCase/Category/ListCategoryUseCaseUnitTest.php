<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryUseCaseUnitTest extends TestCase
{
    public function testGetById()
    {
        $id = (string) Uuid::uuid4()->toString();

        $this->mockEntity = Mockery::mock(Category::class, [
            $id,
            'Test Category'
        ]);

        $this->mockEntity->shouldReceive('id')->andReturn($id);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        
        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')
                            ->with($id)
                            ->andReturn($this->mockEntity);

        $this->mockInputDTO = Mockery::mock(CategoryInputDTO::class, [
            $id,
        ]);

        $useCase = new ListCategoryUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDTO);

        $this->assertInstanceOf(CategoryOutputDTO::class, $response);
        $this->assertEquals($id, $response->id);
        $this->assertEquals('Test Category', $response->name);

        /**
         * Spies
         */

        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')
                            ->with($id)
                            ->andReturn($this->mockEntity);

        $useCase = new ListCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockInputDTO);
        $this->spy->shouldHaveReceived('findById');
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}