<?php

namespace Tests\Unit\UseCase\CastMember;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Core\Domain\Repository\CastMemberRepositoryInterface;

class CreateCastMemberUseCaseUnitTest extends TestCase
{
    public function test_create()
    {
        // arrange
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $useCase = new CreateCastMemberUseCase($mockRepository);

        // action
        $useCase->execute();

        // assert
        $this->assertTrue(true);

        Mockery::close();
    }
}
