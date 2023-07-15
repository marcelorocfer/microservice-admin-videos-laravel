<?php

namespace Tests\Unit\UseCase\CastMember;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\Enum\CastMemberType;
use Dotenv\Repository\RepositoryInterface;
use Core\Domain\Entity\CastMember as Entity;
use Core\UseCase\CastMember\ListCastMemberUseCase;

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
        $mockEntity->shouldReceive('id');
        $mockEntity->shouldReceive('created_at')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(stdClass::class, RepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->times(1)
            ->andReturn($mockEntity);

        $useCase = new ListCastMemberUseCase($mockRepository);
        $useCase->execute();

        Mockery::close();
    }
}
