<?php

namespace Tests\Unit\Domain\Entity;

use DateTime;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\CastMember;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\Enum\CastMemberType;

class CastMemberUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $castMember = new CastMember(
            id: new Uuid($uuid),
            name: 'Name',
            type: CastMemberType::ACTOR,
            created_at: new DateTime(date('Y-m-d H:i:s'))

        );

        $this->assertEquals($uuid, $castMember->id());
        $this->assertEquals('Name', $castMember->name);
        $this->assertEquals(CastMemberType::ACTOR, $castMember->type);
        $this->assertNotEmpty($castMember->created_at());
    }

    public function testAttributesNewEntity()
    {
        $castMember = new CastMember(
            name: 'Name',
            type: CastMemberType::DIRECTOR,

        );

        $this->assertNotEmpty($castMember->id());
        $this->assertEquals('Name', $castMember->name);
        $this->assertEquals(CastMemberType::DIRECTOR, $castMember->type);
        $this->assertNotEmpty($castMember->created_at());
    }
}
