<?php

namespace Tests\Unit\UseCase\Video;

use Mockery;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\Update\UpdateVideoUseCase;
use Core\UseCase\Video\Update\DTO\UpdateInputVideoDTO;
use Core\UseCase\Video\Update\DTO\UpdateOutputVideoDTO;

class UpdateVideoUseCaseUnitTest extends BaseVideoUseCaseUnitTest
{
    public function test_exec_input_output()
    {
        $this->createUseCase();

        $response = $this->useCase->exec(
            input: $this->createMockInputDTO()
        );

        $this->assertInstanceOf(UpdateOutputVideoDTO::class, $response);
    }

    protected function nameActionRepository(): string
    {
        return 'update';
    }

    protected function getUseCase(): string
    {
        return UpdateVideoUseCase::class;
    }

    protected function createMockInputDTO(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
    )
    {
        return Mockery::mock(UpdateInputVideoDTO::class, [
            Uuid::random(),
            'title',
            'description',
            2023,
            70,
            true,
            Rating::RATE14,
            $categoriesIds,
            $genresIds,
            $castMembersIds,
            $videoFile,
            $trailerFile,
            $thumbFile,
            $thumbHalf,
            $bannerFile,
        ]);
    }
}
