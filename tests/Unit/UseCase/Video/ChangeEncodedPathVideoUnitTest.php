<?php

namespace Tests\Unit\UseCase\Video;

use Mockery;
use stdClass;
use Core\Domain\Enum\Rating;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Video as Entity;
use Core\UseCase\Video\DTO\ChangeEncodedVideoDTO;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodedPathVideo;

class ChangeEncodedPathVideoUnitTest extends TestCase
{
    public function testSpies()
    {
        $input = new ChangeEncodedVideoDTO(
            id: 'id-video',
            encodedPath: 'path/video_encoded.ext',
        );

        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
                        ->times(1)
                        ->with($input->id)
                        ->andReturn($this->getEntity());

        $useCase = new ChangeEncodedPathVideo(
            repository: $mockRepository
        );

        $useCase->exec(input: $input);

        Mockery::close();
    }

    private function getEntity(): Entity
    {
        return new Entity(
            title: 'title',
            description: 'description',
            yearLaunched: 2026,
            duration: 1,
            opened: true,
            rating: Rating::L,
        );
    }
}
