<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use DateTime;
use Tests\TestCase;
use App\Models\Genre;
use App\Models\Category;
use App\Models\CastMember;
use Core\Domain\Enum\Rating;
use App\Models\Video as Model;
use Core\Domain\ValueObject\Uuid;
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

    public function testFindById()
    {
        $video = Model::factory()->create();

        $response = $this->repository->findById($video->id);

        $this->assertEquals($video->id, $response->id());
        $this->assertEquals($video->title, $response->title);
    }

    public function testFindAll()
    {
        Model::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertCount(10, $response);
    }

    public function testFindAllWithFilter()
    {
        Model::factory()->count(10)->create();
        Model::factory()->count(10)->create([
            'title' => 'Test',
        ]);

        $response = $this->repository->findAll(
            filter: 'Test'
        );

        $this->assertCount(10, $response);
        $this->assertDatabaseCount('videos', 20);
    }

    /**
     * @dataProvider dataProviderPagination
     *
     * @return void
     */
    public function testPagination(
        int $page,
        int $totalPage,
        int $total = 50,
    ) {
        Model::factory()->count($total)->create();

        $response = $this->repository->paginate(
            page: $page,
            totalPage: $totalPage
        );

        $this->assertCount($totalPage, $response->items());
        $this->assertEquals($total, $response->total());
        $this->assertEquals($page, $response->currentPage());
        $this->assertEquals($totalPage, $response->perPage());
    }

    public function dataProviderPagination(): array
    {
        return [
            [
                'page' => 1,
                'totalPage' => 10,
                'total' => 100,
            ],
            [
                'page' => 2,
                'totalPage' => 15,
            ],
            [
                'page' => 3,
                'totalPage' => 15,
            ],
        ];
    }

    public function testUpdateNotFoundId()
    {
        $this->expectException(NotFoundException::class);

        $entity = new EntityVideo(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2028,
            rating: Rating::L,
            duration: 1,
            opened: true,
        );

        $this->repository->update($entity);
    }

    public function testUpdate()
    {
        $genres = Genre::factory()->count(10)->create();
        $categories = Category::factory()->count(10)->create();
        $castMembers = CastMember::factory()->count(10)->create();

        $videoDB = Model::factory()->create();

        $this->assertDatabaseHas('videos', [
            'title' => $videoDB->title
        ]);

        $entity = new EntityVideo(
            id: new Uuid($videoDB->id),
            title: 'Test',
            description: 'Test',
            yearLaunched: 2028,
            rating: Rating::L,
            duration: 1,
            opened: true,
            created_at: new DateTime($videoDB->created_at),
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

        $entityInDB = $this->repository->update($entity);

        $this->assertDatabaseHas('videos', [
            'title' => 'Test'
        ]);

        $this->assertDatabaseCount('genre_video', 10);
        $this->assertDatabaseCount('category_video', 10);
        $this->assertDatabaseCount('cast_member_video', 10);

        $this->assertEquals($categories->pluck('id')->toArray(), $entityInDB->categorieIds);
        $this->assertEquals($genres->pluck('id')->toArray(), $entityInDB->genreIds);
        $this->assertEquals($castMembers->pluck('id')->toArray(), $entityInDB->castMemberIds);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete('fake_value');
    }

    public function testDelete()
    {
        $video = Model::factory()->create();

        $this->assertDatabaseHas('videos', [
            'id' => $video->id
        ]);

        $this->repository->delete($video->id);

        $this->assertSoftDeleted('videos', [
            'id' => $video->id
        ]);
    }
}
