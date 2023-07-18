<?php

namespace Tests\Unit\UseCase\CastMember;

use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Entity\CastMember as Entity;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\CastMember\UpdateCastMembersUseCase;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Update\{
    CastMemberUpdateInputDTO,
    CastMemberUpdateOutputDTO
};

class UpdateCastMembersUseCaseUnitTest extends TestCase
{
    public function test_update()
    {
        $uuid = (string) RamseyUuid::uuid4();

        // arrange
        $mockEntity = Mockery::mock(Entity::class, [
            'name',
            CastMemberType::ACTOR,
            new ValueObjectUuid($uuid)
        ]);

        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('created_at')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update');

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
                            ->times(1)
                            ->with($uuid)
                            ->andReturn($mockEntity);
        $mockRepository->shouldReceive('update')
                            ->once()
                            ->andReturn($mockEntity);

        $mockInputDTO = Mockery::mock(CastMemberUpdateInputDTO::class, [
            $uuid, 'New name'
        ]);

        $useCase = new UpdateCastMembersUseCase($mockRepository);
        $response = $useCase->execute($mockInputDTO);

        $this->assertInstanceOf(CastMemberUpdateOutputDTO::class, $response);

        Mockery::close();
    }
}
