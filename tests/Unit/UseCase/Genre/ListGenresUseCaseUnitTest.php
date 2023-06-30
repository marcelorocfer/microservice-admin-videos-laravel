<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\ListGenres\ListGenresInputDTO;

class ListGenresUseCaseUnitTest extends TestCase
{
    public function test_usecase()
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $mockInputDTO = Mockery::mock(ListGenresInputDTO::class, [
            'test', 'desc', 1, 15
        ]);

        $useCase = new ListGenresUseCase($mockRepository);
        $useCase->execute($mockInputDTO);

        Mockery::close();
    }
}
