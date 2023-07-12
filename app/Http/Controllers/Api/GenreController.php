<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreGenre;
use App\Http\Resources\GenreResource;
use App\Http\Controllers\Controller;
use Core\UseCase\DTO\Genre\Create\GenreCreateInputDTO;
use Core\UseCase\DTO\Genre\ListGenres\ListGenresInputDTO;
use Core\UseCase\Genre\{ListGenresUseCase, CreateGenreUseCase};
use Illuminate\Http\Response;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ListGenresUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListGenresInputDTO(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15),
            )
        );

        return GenreResource::collection(collect($response->items))
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGenre  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGenre $request, CreateGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new GenreCreateInputDTO(
                name: $request->name,
                is_active: (bool) $request->is_active,
                categoriesId: $request->categories_ids
            )
        );

        return (new GenreResource($response))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
