<?php

namespace Core\Domain\Entity;

use DateTime;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\Factory\VideoValidatorFactory;
use Core\Domain\Notification\NotificationException;

class Video extends Entity
{
    protected array $categorieIds = [];
    protected array $genreIds = [];
    protected array $castMemberIds = [];

    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Uuid $id = null,
        protected bool $published = false,
        protected ?DateTime $created_at = null,
        protected ?Image $thumbFile = null,
        protected ?Image $thumbHalf = null,
        protected ?Image $bannerFile = null,
        protected ?Media $trailerFile = null,
        protected ?Media $videoFile = null,
    ) {
        parent::__construct();

        $this->id = $this->id ?? Uuid::random();
        $this->created_at = $this->created_at ?? new DateTime();

        $this->validation();
    }

    public function update(string $title, string $description): void
    {
        $this->title = $title;
        $this->description = $description;
        $this->validation();
    }

    public function addCategoryId(string $categoryId)
    {
        array_push($this->categorieIds, $categoryId);
    }

    public function removeCategoryId(string $categoryId)
    {
        unset($this->categorieIds[array_search($categoryId, $this->categorieIds)]);
    }

    public function addGenre(string $genreId)
    {
        array_push($this->genreIds, $genreId);
    }

    public function removeGenre(string $genreId)
    {
        unset($this->genreIds[array_search($genreId, $this->genreIds)]);
    }

    public function addCastMember(string $castMemberId)
    {
        array_push($this->castMemberIds, $castMemberId);
    }

    public function removeCastMember(string $castMemberId)
    {
        unset($this->castMemberIds[array_search($castMemberId, $this->castMemberIds)]);
    }

    public function thumbFile(): ?Image
    {
        return $this->thumbFile;
    }

    public function setThumbFile(Image $thumbFile): void
    {
        $this->thumbFile = $thumbFile;
    }

    public function thumbHalf(): ?Image
    {
        return $this->thumbHalf;
    }

    public function setThumbHalf(Image $thumbHalf): void
    {
        $this->thumbHalf = $thumbHalf;
    }

    public function bannerFile(): ?Image
    {
        return $this->bannerFile;
    }

    public function setBannerFile(Image $bannerFile): void
    {
        $this->bannerFile = $bannerFile;
    }

    public function trailerFile(): ?Media
    {
        return $this->trailerFile;
    }

    public function setTrailerFile(Media $trailerFile): void
    {
        $this->trailerFile = $trailerFile;
    }

    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }

    public function setVideoFile(Media $videoFile): void
    {
        $this->videoFile = $videoFile;
    }

    protected function validation()
    {
        VideoValidatorFactory::create()->validate($this);

        if ($this->notification->hasErrors()) {
            throw new NotificationException(
                $this->notification->messages('video')
            );
        }
    }
}
