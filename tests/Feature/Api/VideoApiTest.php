<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Video;
use Illuminate\Http\Response;

class VideoApiTest extends TestCase
{
    protected $endpoint = 'api/videos';

    /**
     * @test
     */
    public function empty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function pagination()
    {
        $videos = Video::factory()->count(20)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'year_launched',
                    'opened',
                    'rating',
                    'duration',
                    'created_at',
                ]
            ],
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from',
            ]
        ]);
    }
}
