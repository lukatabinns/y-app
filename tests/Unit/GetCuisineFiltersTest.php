<?php

namespace Tests\Feature;

use App\Models\Cuisine;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetCuisineFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_cuisine_filters_returns_sorted_data_with_counts()
    {
        // Arrange: Create test data
        $cuisine1 = Cuisine::factory()->create(['name' => 'Italian', 'slug' => 'italian']);
        $cuisine2 = Cuisine::factory()->create(['name' => 'Chinese', 'slug' => 'chinese']);
        $cuisine3 = Cuisine::factory()->create(['name' => 'Mexican', 'slug' => 'mexican']);

        Menu::factory()->create(['cuisine_id' => $cuisine1->id, 'status' => 'live']);
        Menu::factory()->create(['cuisine_id' => $cuisine1->id, 'status' => 'live']);
        Menu::factory()->create(['cuisine_id' => $cuisine2->id, 'status' => 'live']);
        Menu::factory()->create(['cuisine_id' => $cuisine3->id, 'status' => 'inactive']); // Not live

        // Act: Call the endpoint
        $response = $this->getJson(route('get.cuisine.filters'));

        // Assert: Check the response structure and data
        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'slug',
                'live_set_menus_count',
            ],
        ]);

        $response->assertJson([
            ['name' => 'Italian', 'live_set_menus_count' => 2],
            ['name' => 'Chinese', 'live_set_menus_count' => 1],
        ]);

        // Ensure that cuisines are sorted by live_set_menus_count in descending order
        $this->assertEquals('Italian', $response->json()[0]['name']);
        $this->assertEquals('Chinese', $response->json()[1]['name']);
    }

    public function test_get_cuisine_filters_returns_empty_when_no_live_set_menus()
    {
        // Arrange: Create cuisines with no live set menus
        Cuisine::factory()->create(['name' => 'Italian', 'slug' => 'italian']);
        Cuisine::factory()->create(['name' => 'Chinese', 'slug' => 'chinese']);

        // Act: Call the endpoint
        $response = $this->getJson(route('get.cuisine.filters'));

        // Assert: Response is empty
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }
}
