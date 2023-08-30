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
     * @dataProvider dataProviderPagination
     */
    public function pagination(
        int $total,
        int $totalCurrentPage,
        int $page = 1,
        int $perPage = 15,
    ) {
        Video::factory()->count($total)->create();

        $params = http_build_query([
            'page' => $page,
            'per_page' => $perPage,
            'order' => 'DESC',
        ]);

        $response = $this->getJson("$this->endpoint?$params");

        $response->assertOk();
        $response->assertJsonCount($totalCurrentPage, 'data');
        $response->assertJsonPath('meta.current_page', $page);
        $response->assertJsonPath('meta.per_page', $perPage);
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

    protected function dataProviderPagination(): array
    {
        return [
            'test empty' => [
                'total' => 0,
                'totalCurrentPage' => 0,
                'page' => 1,
                'perPage' => 15,
            ],
            'test with total two pages' => [
                'total' => 20,
                'totalCurrentPage' => 15,
                'page' => 1,
                'perPage' => 15,
            ],
            'test page two' => [
                'total' => 20,
                'totalCurrentPage' => 5,
                'page' => 2,
                'perPage' => 15,
            ],
            'test page four' => [
                'total' => 40,
                'totalCurrentPage' => 10,
                'page' => 4,
                'perPage' => 10,
            ],
        ];
    }
}
