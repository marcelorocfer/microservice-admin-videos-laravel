<?php

namespace Tests\Feature\Core\UseCase\Video;

use Tests\TestCase;
use App\Models\Genre;
use App\Models\Category;
use App\Models\CastMember;
use Core\Domain\Enum\Rating;
use Illuminate\Http\UploadedFile;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

class CreateVideoUseCaseTest extends TestCase
{
    public function test_create()
    {
        $useCase = new CreateVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
            $this->app->make(FileStorageInterface::class),
            $this->app->make(VideoEventManagerInterface::class),

            $this->app->make(CategoryRepositoryInterface::class),
            $this->app->make(GenreRepositoryInterface::class),
            $this->app->make(CastMemberRepositoryInterface::class),
        );

        $categoriesIds = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count(3)->create()->pluck('id')->toArray();
        $castMembersIds = CastMember::factory()->count(3)->create()->pluck('id')->toArray();

        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getFilename(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $input = new CreateInputVideoDTO(
            title: 'Test Title',
            description: 'Test Description',
            yearLaunched: 2028,
            duration: 120,
            opened: true,
            rating: Rating::L,
            categories: $categoriesIds,
            genres: $genresIds,
            castMembers: $castMembersIds,
            videoFile: $file,
        );

        $response = $useCase->exec($input);

        $this->assertEquals($input->title, $response->title);
        $this->assertEquals($input->description, $response->description);
        $this->assertEquals($input->yearLaunched, $response->yearLaunched);
        $this->assertEquals($input->duration, $response->duration);
        $this->assertEquals($input->opened, $response->opened);
        $this->assertEquals($input->rating, $response->rating);

        $this->assertCount(count($input->categories), $response->categories);
        $this->assertCount(count($input->genres), $response->genres);
        $this->assertCount(count($input->castMembers), $response->castMembers);

        $this->assertNotNull($response->videoFile);
        $this->assertNull($response->trailerFile);
        $this->assertNull($response->bannerFile);
        $this->assertNull($response->thumbFile);
        $this->assertNull($response->thumbHalf);
    }
}
