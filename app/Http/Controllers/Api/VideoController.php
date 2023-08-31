<?php

namespace App\Http\Controllers\Api;

use Core\Domain\Enum\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Paginate\ListVideosUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\List\DTO\ListInputVideoUseCase;
use Core\UseCase\Video\Paginate\DTO\PaginateInputVideoDTO;

class VideoController extends Controller
{
    public function index(Request $request, ListVideosUseCase $useCase)
    {
        $response = $useCase->exec(
            input: new PaginateInputVideoDTO(
                filter: $request->filter ?? '',
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                per_page: (int) $request->get('per_page', 15),
            )
        );

        return VideoResource::collection(collect($response->items))
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

    public function show(ListVideoUseCase $useCase, $id)
    {
        $response = $useCase->exec(new ListInputVideoUseCase($id));

        return new VideoResource($response);
    }

    public function store(CreateVideoUseCase $useCase, Request $request)
    {
        $response = $useCase->exec(new CreateInputVideoDTO(
            title: $request->title,
            description: $request->description,
            yearLaunched: $request->year_launched,
            duration: $request->duration,
            opened: $request->opened,
            rating: Rating::from($request->rating),
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
        ));

        return (new VideoResource($response))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }
}
