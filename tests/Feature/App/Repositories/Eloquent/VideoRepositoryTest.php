<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\Genre;
use App\Models\Category;
use App\Models\CastMember;
use Core\Domain\Enum\Rating;
use App\Models\Video as Model;
use Core\Domain\Entity\Video as EntityVideo;
use Core\Domain\Exceptions\NotFoundException;
use App\Repositories\Eloquent\VideoRepository;
use Core\Domain\Repository\VideoRepositoryInterface;

class VideoRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new VideoRepository(
            new Model()
        );
    }

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(VideoRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new EntityVideo(
            title: 'Test Title',
            description: 'Test description',
            yearLaunched: 2028,
            rating: Rating::L,
            duration: 1,
            opened: true,
        );

        $this->repository->insert($entity);

        $this->assertDatabaseHas('videos', [
            'id' => $entity->id()
        ]);
    }

    public function testInsertWithRelationships()
    {
        $genres = Genre::factory()->count(4)->create();
        $categories = Category::factory()->count(4)->create();
        $castMembers = CastMember::factory()->count(4)->create();

        $entity = new EntityVideo(
            title: 'Test Title',
            description: 'Test description',
            yearLaunched: 2028,
            rating: Rating::L,
            duration: 1,
            opened: true,
        );

        foreach ($genres as $genre) {
            $entity->addGenre($genre->id);
        }

        foreach ($categories as $category) {
            $entity->addCategoryId($category->id);
        }

        foreach ($castMembers as $castMember) {
            $entity->addCastMember($castMember->id);
        }

        $entityInDB = $this->repository->insert($entity);

        $this->assertDatabaseHas('videos', [
            'id' => $entity->id()
        ]);

        $this->assertDatabaseCount('genre_video', 4);
        $this->assertDatabaseCount('category_video', 4);
        $this->assertDatabaseCount('cast_member_video', 4);

        $this->assertEquals($categories->pluck('id')->toArray(), $entityInDB->categorieIds);
        $this->assertEquals($genres->pluck('id')->toArray(), $entityInDB->genreIds);
        $this->assertEquals($castMembers->pluck('id')->toArray(), $entityInDB->castMemberIds);
    }

    public function testNotFoundVideo()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->findById('fake_value');
    }
}
