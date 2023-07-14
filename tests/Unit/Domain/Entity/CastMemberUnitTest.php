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
    }
}
