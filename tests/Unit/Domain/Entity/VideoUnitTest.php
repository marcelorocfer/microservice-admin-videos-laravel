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

        $this->assertEquals($uuid, $entity->id());
        $this->assertEquals('Title', $entity->title);
        $this->assertEquals('Description', $entity->description);
        $this->assertEquals(2029, $entity->yearLaunched);
        $this->assertEquals(90, $entity->duration);
        $this->assertEquals(true, $entity->opened);
        $this->assertEquals(Rating::RATE12, $entity->rating);
        $this->assertEquals(true, $entity->published);
    }

    public function testId()
    {
        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertNotEmpty($entity->id());
    }

    public function testAddCategoryId()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertCount(0, $entity->categoriesId);
        $entity->addCategoryId(
            categoryId: $categoryId,
        );
        $entity->addCategoryId(
            categoryId: $categoryId,
        );
        $this->assertCount(2, $entity->categoriesId);
    }

    public function testRemoveCategoryId()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $entity->addCategoryId(
            categoryId: $categoryId,
        );

        $entity->addCategoryId(
            categoryId: 'uuid',
        );

        $this->assertCount(2, $entity->categoriesId);

        $entity->removeCategoryId(
            categoryId: $categoryId,
        );

        $this->assertCount(1, $entity->categoriesId);
    }

    public function testAddGenre()
    {
        $genreId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertCount(0, $entity->genresId);
        $entity->addGenre(
            genreId: $genreId,
        );
        $entity->addGenre(
            genreId: $genreId,
        );
        $this->assertCount(2, $entity->genresId);
    }

    public function testRemoveGenre()
    {
        $genreId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $entity->addGenre(
            genreId: $genreId,
        );

        $entity->addGenre(
            genreId: 'uuid',
        );

        $this->assertCount(2, $entity->genresId);

        $entity->removeGenre(
            genreId: $genreId,
        );

        $this->assertCount(1, $entity->genresId);
    }
}
