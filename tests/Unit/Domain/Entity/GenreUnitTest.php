<?php

namespace Tests\Unit\Domain\Entity;

use DateTime;
use Core\Domain\Entity\Genre;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

class GenreUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            id: new Uuid($uuid),
            name: 'New Genre',
            is_active: false,
            created_at: new DateTime($date),
        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertEquals(false, $genre->is_active);
        $this->assertEquals($date, $genre->created_at());
    }

    public function testAttributesCreate()
    {
        $genre = new Genre(
            name: 'New Genre',
        );

        $this->assertNotEmpty($genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertEquals(true, $genre->is_active);
        $this->assertNotEmpty($genre->created_at());
    }

}
