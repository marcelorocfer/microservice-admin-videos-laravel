<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\CastMember as Model;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Entity\CastMember as Entity;
use App\Repositories\Eloquent\CastMemberRepository;
use Core\Domain\Repository\CastMemberRepositoryInterface;

class CastMemberRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CastMemberRepository(new Model());
    }

    public function testCheckImplementsInterfaceRepository()
    {
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new Entity(
            name: 'test',
            type: CastMemberType::ACTOR,
        );

        $response = $this->repository->insert($entity);

        $this->assertDatabaseHas('cast_members', [
            'id' => $entity->id(),
        ]);
        $this->assertEquals($entity->name, $response->name);
    }
}
