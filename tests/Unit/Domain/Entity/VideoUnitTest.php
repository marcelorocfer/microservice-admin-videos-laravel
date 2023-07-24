<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Enum\Rating;
use Core\Domain\Entity\Video;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

class VideoUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $entity = new Video(
            id: new Uuid($uuid),
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,

        );
    }
}
