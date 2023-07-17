<?php

namespace Tests\Unit\UseCase\CastMember;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Tests\Unit\UseCase\UseCaseTrait;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\List\{
    ListCastMembersInputDTO,
    ListCastMembersOutputDTO,
};

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
            'filter', 'desc', 1, 15
        ]);

        $response = $useCase->execute($mockInputDTO);

        $this->assertInstanceOf(ListCastMembersOutputDTO::class, $response);

        Mockery::close();
    }
}
