<?php

namespace Tests\Unit\UseCase\CastMember;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Core\Domain\Repository\CastMemberRepositoryInterface;
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
        $useCase->execute();

        Mockery::close();
    }
}
