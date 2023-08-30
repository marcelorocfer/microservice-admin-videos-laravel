<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Video;

class VideoApiTest extends TestCase
{
    protected $endpoint = 'api/videos';

    /**
     * @test
     */
    public function empty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function pagination()
    {
        $videos = Video::factory()->count(20)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertOk();
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.per_page', 15);
        $response->assertJsonPath('meta.current_page', 1);
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
