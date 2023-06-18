<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteOutputDTO;

class DeleteCategoryUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        
        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('delete')->andReturn(true);

        $this->mockInputDTO = Mockery::mock(CategoryInputDTO::class, [$uuid]);

        $useCase = new DeleteCategoryUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDTO);

        $this->assertInstanceOf(CategoryDeleteOutputDTO::class, $response);
        $this->assertTrue($response->success);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('delete')->andReturn(true);   
        $useCase = new DeleteCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockInputDTO);     
        $this->spy->shouldHaveReceived('delete');
    }

    public function testDeleteFalse()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        
        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('delete')->andReturn(false);

        $this->mockInputDTO = Mockery::mock(CategoryInputDTO::class, [$uuid]);

        $useCase = new DeleteCategoryUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDTO);

        $this->assertInstanceOf(CategoryDeleteOutputDTO::class, $response);
        $this->assertFalse($response->success);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}