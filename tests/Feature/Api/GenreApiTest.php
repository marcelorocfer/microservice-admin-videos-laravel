<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\Genre as Model;
use App\Models\Category as CategoryModel;

class GenreApiTest extends TestCase
{
    protected $endpoint = '/api/genres';

    public function test_list_all_empty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function test_list_all()
    {
        Model::factory()->count(20)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
    }

    public function test_store()
    {
        $categories = CategoryModel::factory()->count(10)->create();

        $response = $this->postJson($this->endpoint, [
            'name' => 'New genre',
            'is_active' => true,
            'categories_ids' => $categories->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
