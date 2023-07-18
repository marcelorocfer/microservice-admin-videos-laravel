<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\CastMember as Model;
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
}
