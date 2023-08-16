<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use Core\Domain\Enum\Rating;
use App\Models\Video as Model;
use Core\Domain\Entity\Video as EntityVideo;
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
}
