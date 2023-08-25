<?php

namespace Tests\Feature\Core\UseCase\Video;

use Tests\TestCase;

class CreateVideoUseCaseTest extends TestCase
{
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
