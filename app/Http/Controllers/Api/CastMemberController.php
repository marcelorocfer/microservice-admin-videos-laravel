<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CastMemberResource;
use Core\UseCase\DTO\CastMember\CastMemberInputDTO;
use Core\UseCase\DTO\CastMember\List\ListCastMembersInputDTO;
use Core\UseCase\DTO\CastMember\Create\CastMemberCreateInputDTO;
use Core\UseCase\DTO\CastMember\Update\CastMemberUpdateInputDTO;
use Illuminate\Http\{
    Request,
    Response
};
use App\Http\Requests\{
    StoreCastMemberRequest,
    UpdateCastMemberRequest
};
use Core\UseCase\CastMember\{
    ListCastMemberUseCase,
    CreateCastMemberUseCase,
    DeleteCastMemberUseCase,
    ListCastMembersUseCase,
    UpdateCastMemberUseCase,
};

class CastMemberController extends Controller
{
    public function index(Request $request, ListCastMembersUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListCastMembersInputDTO(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                per_page: (int) $request->get('total_page', 15),
            )
        );

        return CastMemberResource::collection(collect($response->items))
                                ->additional([
                                    'meta' => [
                                        'total' => $response->total,
                                        'current_page' => $response->current_page,
                                        'last_page' => $response->last_page,
                                        'first_page' => $response->first_page,
                                        'per_page' => $response->per_page,
                                        'to' => $response->to,
                                        'from' => $response->from,
                                    ]
                                ]);
    }

    public function store(StoreCastMemberRequest $request, CreateCastMemberUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new CastMemberCreateInputDTO(
                name: $request->name,
                type: (int) $request->type,
            )
        );

        return (new CastMemberResource($response))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCastMemberUseCase $useCase, $id)
    {
        $castMember = $useCase->execute(new CastMemberInputDTO($id));

        return (new CastMemberResource($castMember))->response();
    }

    public function update(UpdateCastMemberRequest $request, UpdateCastMemberUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new CastMemberUpdateInputDTO(
                id: $id,
                name: $request->name,
            )
        );

        return (new CastMemberResource($response))->response();
    }

    public function destroy(DeleteCastMemberUseCase $useCase, $id)
    {
        $useCase->execute(new CastMemberInputDTO($id));

        return response()->noContent();
    }
}
