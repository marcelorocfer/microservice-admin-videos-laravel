<?php

namespace Tests\Feature\Core\UseCase\Video;

use Tests\TestCase;
use App\Models\Video as Model;
use Core\UseCase\Video\DTO\ChangeEncodedVideoDTO;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodedPathVideo;

class ChangeEncodedPathVideoTest extends TestCase
{
    public function testIfUpdatedMediaInDatabase()
    {
        $video = Model::factory()->create();

        $useCase = new ChangeEncodedPathVideo(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $input = new ChangeEncodedVideoDTO(
            id: $video->id,
            encodedPath: 'path-id/video_encoded.ext',
        );

        $useCase->exec($input);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $input->id,
            'encoded_path' => $input->encodedPath,
        ]);
    }
}
