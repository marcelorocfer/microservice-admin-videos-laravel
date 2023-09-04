<?php

namespace App\Http\Controllers\Api;

use App\Adapters\ApiAdapter;
use Core\Domain\Enum\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use Core\UseCase\Video\Update\UpdateVideoUseCase;
use Core\UseCase\Video\Paginate\ListVideosUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\Delete\DTO\DeleteInputVideoDTO;
use Core\UseCase\Video\List\DTO\ListInputVideoUseCase;
use Core\UseCase\Video\Update\DTO\UpdateInputVideoDTO;
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

        return (new ApiAdapter($response))->toJson();
    }

    public function show(ListVideoUseCase $useCase, $id)
    {
        $response = $useCase->exec(new ListInputVideoUseCase($id));

        return ApiAdapter::json($response);
    }

    public function store(CreateVideoUseCase $useCase, StoreVideoRequest $request)
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
            videoFile: getArrayFile($request->file('video_file')),
            trailerFile: getArrayFile($request->file('trailer_file')),
            bannerFile: getArrayFile($request->file('banner_file')),
            thumbFile: getArrayFile($request->file('thumb_file')),
            thumbHalf: getArrayFile($request->file('thumb_half_file')),
        ));

        return ApiAdapter::json($response, Response::HTTP_CREATED);
    }



    public function update(UpdateVideoUseCase $useCase, UpdateVideoRequest $request, $id)
    {
        if ($file = $request->file('video_file')) {
            $videoFile = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'size' => $file->getSize(),
                'error' => $file->getError(),
                'type' => $file->getType(),
            ];
        }

        if ($file = $request->file('trailer_file')) {
            $trailerFile = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'size' => $file->getSize(),
                'error' => $file->getError(),
                'type' => $file->getType(),
            ];
        }

        if ($file = $request->file('banner_file')) {
            $bannerFile = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'size' => $file->getSize(),
                'error' => $file->getError(),
                'type' => $file->getType(),
            ];
        }

        if ($file = $request->file('thumb_file')) {
            $thumbFile = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'size' => $file->getSize(),
                'error' => $file->getError(),
                'type' => $file->getType(),
            ];
        }

        if ($file = $request->file('thumb_half_file')) {
            $thumbHalfFile = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'size' => $file->getSize(),
                'error' => $file->getError(),
                'type' => $file->getType(),
            ];
        }

        $response = $useCase->exec(new UpdateInputVideoDTO(
            id: $id,
            title: $request->title,
            description: $request->description,
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
            videoFile: $videoFile ?? null,
            trailerFile: $trailerFile ?? null,
            bannerFile: getArrayFile($request->file('video_file')),
            thumbFile: $thumbFile ?? null,
            thumbHalf: $thumbHalfFile ?? null,
        ));

        return ApiAdapter::json($response);
    }

    public function destroy(DeleteVideoUseCase $useCase, $id)
    {
        $useCase->exec(new DeleteInputVideoDTO(id: $id));

        return response()->noContent();
    }
}
