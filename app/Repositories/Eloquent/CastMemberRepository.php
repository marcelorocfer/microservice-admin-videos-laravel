<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Entity\CastMember;
use App\Models\CastMember as Model;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;

class CastMemberRepository implements CastMemberRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(CastMember $castMember): CastMember
    {
        $dataDB = $this->model->create([
            'id' => $castMember->id(),
            'name' => $castMember->name,
            'type' => $castMember->type->value,
            'created_at' => $castMember->created_at(),
        ]);

        return $this->convertToEntity($dataDB);
    }

    public function findById(string $id): CastMember
    {

    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {

    }

    public function update(CastMember $castMember): CastMember
    {

    }

    public function delete(string $id): bool
    {

    }

    private function convertToEntity(Model $model): CastMember
    {
        return new CastMember(
            id: new ValueObjectUuid($model->id),
            name: $model->name,
            type: CastMemberType::from($model->type),
            created_at: $model->created_at
        );
    }
}
