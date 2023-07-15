<?php

namespace Tests\Unit\UseCase\CastMember;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Entity\CastMember as Entity;
use Core\UseCase\CastMember\ListCastMemberUseCase;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\{
    CastMemberInputDTO,
    CastMemberOutputDTO
};

class ListCastMemberUseCaseUnitTest extends TestCase
{
    public function test_list()
    {
        $uuid = (string) RamseyUuid::uuid4();

        // arrange
        $mockEntity = Mockery::mock(Entity::class, [
            'name',
            CastMemberType::ACTOR,
            new Uuid($uuid)
        ]);
        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('created_at')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->times(1)
            ->andReturn($mockEntity);

        $mockInputDTO = Mockery::mock(CastMemberInputDTO::class, [$uuid]);

        $useCase = new ListCastMemberUseCase($mockRepository);
        $response = $useCase->execute($mockInputDTO);

        $this->assertInstanceOf(CastMemberOutputDTO::class, $response);

        Mockery::close();
    }
}
