<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Core\UseCase\DTO\CastMember\List\ListCastMembersInputDTO;
use Core\UseCase\DTO\CastMember\List\ListCastMembersOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\UseCase\UseCaseTrait;

class ListCastMembersUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function test_list()
    {
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
            ->once()
            ->andReturn($this->mockPagination());

        $useCase = new ListCastMembersUseCase($mockRepository);

        $mockInputDTO = Mockery::mock(ListCastMembersInputDTO::class, [
            'filter', 'desc', 1, 15,
        ]);

        $response = $useCase->execute($mockInputDTO);

        $this->assertInstanceOf(ListCastMembersOutputDTO::class, $response);

        Mockery::close();
    }
}
