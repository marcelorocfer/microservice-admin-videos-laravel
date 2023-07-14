<?php

namespace Tests\Unit\UseCase\CastMember;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Create\{
    CastMemberCreateInputDTO,
    CastMemberCreateOutputDTO,
};

class CreateCastMemberUseCaseUnitTest extends TestCase
{
    public function test_create()
    {
        // arrange
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $useCase = new CreateCastMemberUseCase($mockRepository);

        $mockDTO = Mockery::mock(CastMemberCreateInputDTO::class, [
            'name', 1
        ]);

        // action
        $response = $useCase->execute($mockDTO);

        // assert
        $this->assertInstanceOf(CastMemberCreateOutputDTO::class, $response);

        Mockery::close();
    }
}
