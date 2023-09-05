<?php

namespace Tests\Unit\UseCase\Video;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodedPathVideo;

class ChangeEncodedPathVideoUnitTest extends TestCase
{
    public function testSpies()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $useCase = new ChangeEncodedPathVideo(
            repository: $mockRepository
        );
    }
}
