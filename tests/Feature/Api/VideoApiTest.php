<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Http\Response;

class VideoApiTest extends TestCase
{
    protected $endpoint = 'api/videos';

    public function testEmpty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
    }
}
