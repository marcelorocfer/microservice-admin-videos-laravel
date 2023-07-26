<?php

namespace Tests\Unit\Domain\Entity;

use DateTime;
use Core\Domain\Enum\Rating;
use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\Exceptions\EntityValidationException;
use Core\Domain\ValueObject\{
    Image,
    Media,
    Uuid
};

class VideoUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $entity = new Video(
            id: new Uuid($uuid),
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            created_at: new DateTime($date),
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

    public function testIdAndCreatedAt()
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
        $this->assertNotEmpty($entity->created_at());
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

        $this->assertCount(0, $entity->categorieIds);
        $entity->addCategoryId(
            categoryId: $categoryId,
        );
        $entity->addCategoryId(
            categoryId: $categoryId,
        );
        $this->assertCount(2, $entity->categorieIds);
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

        $this->assertCount(2, $entity->categorieIds);

        $entity->removeCategoryId(
            categoryId: $categoryId,
        );

        $this->assertCount(1, $entity->categorieIds);
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

        $this->assertCount(0, $entity->genreIds);
        $entity->addGenre(
            genreId: $genreId,
        );
        $entity->addGenre(
            genreId: $genreId,
        );
        $this->assertCount(2, $entity->genreIds);
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

        $this->assertCount(2, $entity->genreIds);

        $entity->removeGenre(
            genreId: $genreId,
        );

        $this->assertCount(1, $entity->genreIds);
    }

    public function testAddCastMember()
    {
        $castMemberId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertCount(0, $entity->castMemberIds);
        $entity->addCastMember(
            castMemberId: $castMemberId,
        );
        $entity->addCastMember(
            castMemberId: $castMemberId,
        );
        $this->assertCount(2, $entity->castMemberIds);
    }

    public function testRemoveCastMember()
    {
        $castMemberId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $entity->addCastMember(
            castMemberId: $castMemberId,
        );

        $entity->addCastMember(
            castMemberId: 'uuid',
        );

        $this->assertCount(2, $entity->castMemberIds);

        $entity->removeCastMember(
            castMemberId: $castMemberId,
        );

        $this->assertCount(1, $entity->castMemberIds);
    }

    public function testValueObjectImage()
    {
        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            thumbFile: new Image('test-path/image.png'),
        );

        $this->assertNotNull($entity->thumbFile()->path());
        $this->assertInstanceOf(Image::class, $entity->thumbFile());
        $this->assertEquals('test-path/image.png', $entity->thumbFile()->path());
    }

    public function testValueObjectImageToThumbHalf()
    {
        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            thumbHalf: new Image('test-path/image.png'),
        );

        $this->assertNotNull($entity->thumbHalf());
        $this->assertInstanceOf(Image::class, $entity->thumbHalf());
        $this->assertEquals('test-path/image.png', $entity->thumbHalf()->path());
    }

    public function testValueObjectMedia()
    {
        $trailerFile = new Media(
            filePath: 'path/trailer.mp4',
            mediaStatus: MediaStatus::PENDING,
            encodedPath: 'path/encoded.extension',
        );

        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            trailerFile: $trailerFile,
        );

        $this->assertNotNull($entity->trailerFile());
        $this->assertInstanceOf(Media::class, $entity->trailerFile());
        $this->assertEquals('path/trailer.mp4', $entity->trailerFile()->filePath);
    }

    public function testValueObjectMediaVideo()
    {
        $videoFile = new Media(
            filePath: 'path/video.mp4',
            mediaStatus: MediaStatus::COMPLETED,
        );

        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            videoFile: $videoFile,
        );

        $this->assertNotNull($entity->videoFile());
        $this->assertInstanceOf(Media::class, $entity->videoFile());
        $this->assertEquals('path/video.mp4', $entity->videoFile()->filePath);
    }

    public function testValueObjectImageToBannerFile()
    {
        $entity = new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            bannerFile: new Image('test-path/banner.png'),
        );

        $this->assertNotNull($entity->bannerFile());
        $this->assertInstanceOf(Image::class, $entity->bannerFile());
        $this->assertEquals('test-path/banner.png', $entity->bannerFile()->path());
    }

    public function testValidations()
    {
        $this->expectException(EntityValidationException::class);

        new Video(
            title: 'Ti',
            description: 'De',
            yearLaunched: 2029,
            duration: 90,
            opened: true,
            rating: Rating::RATE12,
        );
    }
}
