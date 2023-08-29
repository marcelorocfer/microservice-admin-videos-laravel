<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Video;
use Illuminate\Http\Response;

class VideoApiTest extends TestCase
{
    protected $endpoint = 'api/videos';

    public function testEmpty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testPagination()
    {
        $videos = Video::factory()->count(20)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
    }
}
