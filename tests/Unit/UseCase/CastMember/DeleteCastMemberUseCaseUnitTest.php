<?php

namespace Tests\Unit\UseCase\CastMember;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\UseCase\DTO\CastMember\CastMemberInputDTO;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Delete\DeleteCastMemberOutputDTO;

class DeleteCastMemberUseCaseUnitTest extends TestCase
{
    public function test_delete()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
                            ->once()
                            ->andReturn(true);

        $mockInputDTO = Mockery::mock(CastMemberInputDTO::class, [$uuid]);

        $useCase = new DeleteCastMemberUseCase($mockRepository);
        $response = $useCase->execute($mockInputDTO);

        $this->assertInstanceOf(DeleteCastMemberOutputDTO::class, $response);

        Mockery::close();
    }
}
