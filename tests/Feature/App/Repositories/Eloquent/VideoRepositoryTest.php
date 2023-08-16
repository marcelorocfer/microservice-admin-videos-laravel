<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\Video as Model;
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
}
