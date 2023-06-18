<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDTO;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesOutputDTO;

class ListCategoriesUseCaseUnitTest extends TestCase
{
    public function testListCategoriesEmpty()
    {      
        $mockPagination = $this->mockPagination();

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('paginate')->andReturn($mockPagination);

        $this->mockInputDTO = Mockery::mock(ListCategoriesInputDTO::class, ['filter', 'desc']);

        $useCase = new ListCategoriesUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDTO);

        $this->assertCount(0, $response->items);
        $this->assertInstanceOf(ListCategoriesOutputDTO::class, $response);

        /**      
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('paginate')->andReturn($mockPagination);
        $useCase = new ListCategoriesUseCase($this->spy);
        $useCase->execute($this->mockInputDTO);
        $this->spy->shouldHaveReceived('paginate');
    }

    public function testListCategories()
    {      
        $register = new stdClass();
        $register->id = 'id';
        $register->name = 'name';
        $register->description = 'description';
        $register->isActive = 'isActive';
        $register->created_at = 'created_at';
        $register->updated_at = 'updated_at';
        $register->deleted_at = 'deleted_at';

        $mockPagination = $this->mockPagination([
            $register
        ]);

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('paginate')->andReturn($mockPagination);

        $this->mockInputDTO = Mockery::mock(ListCategoriesInputDTO::class, ['filter', 'desc']);

        $useCase = new ListCategoriesUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDTO);

        $this->assertCount(1, $response->items);
        $this->assertInstanceOf(stdClass::class, $response->items[0]);
        $this->assertInstanceOf(ListCategoriesOutputDTO::class, $response);
    }

    protected function mockPagination(array $items = []) 
    {
        $this->mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockPagination->shouldReceive('items')->andReturn($items);
        $this->mockPagination->shouldReceive('total')->andReturn(0);
        $this->mockPagination->shouldReceive('firstPage')->andReturn(0);
        $this->mockPagination->shouldReceive('lastPage')->andReturn(0);
        $this->mockPagination->shouldReceive('currentPage')->andReturn(0);
        $this->mockPagination->shouldReceive('perPage')->andReturn(0);
        $this->mockPagination->shouldReceive('to')->andReturn(0);
        $this->mockPagination->shouldReceive('from')->andReturn(0);

        return $this->mockPagination;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}