<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\CastMember as Model;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Entity\CastMember as Entity;
use Core\Domain\Exceptions\NotFoundException;
use App\Repositories\Eloquent\CastMemberRepository;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
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

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->findById('fake_id');
    }

    public function testFindById()
    {
        $castMember = Model::factory()->create();

        $response = $this->repository->findById($castMember->id);
        $this->assertEquals($castMember->id, $response->id());
        $this->assertEquals($castMember->name, $response->name);
    }

    public function testFindAllEmpty()
    {
        $response = $this->repository->findAll();
        $this->assertCount(0, $response);
    }

    public function testFindAll()
    {
        $castMembers = Model::factory()->count(50)->create();
        $response = $this->repository->findAll();
        $this->assertCount(count($castMembers), $response);
    }

    public function testPagination()
    {
        Model::factory()->count(20)->create();

        $response = $this->repository->paginate();
        $this->assertCount(15, $response->items());
        $this->assertEquals(20, $response->total());
    }

    public function testPaginationPageTwo()
    {
        Model::factory()->count(80)->create();

        $response = $this->repository->paginate(
            totalPage: 10
        );

        $this->assertCount(10, $response->items());
        $this->assertEquals(80, $response->total());
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundException::class);

        $entity = new Entity(
            name: 'test',
            type: CastMemberType::DIRECTOR
        );

        $this->repository->update($entity);
    }

    public function testUpdate()
    {
        $castMember = Model::factory()->create();

        $entity = new Entity(
            id: new ValueObjectUuid($castMember->id),
            name: 'New name',
            type: CastMemberType::DIRECTOR
        );

        $response = $this->repository->update($entity);

        $this->assertNotEquals($castMember->name, $response->name);
        $this->assertEquals('New name', $response->name);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete('fake_id');
    }

    public function testDelete()
    {
        $castMember = Model::factory()->create();

        $this->repository->delete($castMember->id);

        $this->assertSoftDeleted('cast_members', [
            'id' => $castMember->id
        ]);
    }
}
