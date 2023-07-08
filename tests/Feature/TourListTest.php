<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TourListTest extends TestCase
{

    use RefreshDatabase;

    public function test_tour_list_By_travel_slug_returns_correct_tours(): void
    {
        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create(['travel_id' => $travel->id]);

        $response = $this->get("/api/v1/travels/{$travel->slug}/tours");
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function test_tour_price_is_shown_correctly(): void
    {
        $travel = Travel::factory()->create();
        Tour::factory()->create(['travel_id' => $travel->id, 'price' => 123.45]);
        $response = $this->get("/api/v1/travels/{$travel->slug}/tours");
        $response->assertStatus(200);
        $response->assertJsonFragment(['price' => '123.45']);
    }

    public function test_tour_list_returns_pagination(): void
    {

        $toursPage = 15;
        echo $toursPage;
        $travel = Travel::factory()->create();
        Tour::factory($toursPage + 1)->create(['travel_id' => $travel->id, 'price' => 123.45]);
        $response = $this->get("/api/v1/travels/{$travel->slug}/tours");
        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_tour_list_validate_sortOrder(): void
    {
        $travel = Travel::factory()->create();
        $response = $this->getJson('api/v1/travels/' . $travel->slug . '/tours?sortOrder=random');
        $response->assertStatus(422);
        $response->assertJsonFragment(['sortOrder' => ['The selected sort order is invalid.']]);
    }

    public function  test_tour_list_returns_priceOrder_correctly(): void
    {
        $travel = Travel::factory()->create();

        $cheapTour = Tour::factory()->create(['travel_id' => $travel->id, 'price' => 99]);
        $expensiveTour = Tour::factory()->create(['travel_id' => $travel->id, 'price' => 100]);

        $endpoint = 'api/v1/travels/' . $travel->slug . '/tours';
        $response = $this->get($endpoint . '?sortBy=price&sortOrder=desc');
        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $expensiveTour->id);
        $response->assertJsonPath('data.1.id', $cheapTour->id);
    }

    public function test_tour_priceTo_filter(): void
    {
        $travel = Travel::factory()->create();
        Tour::factory()->create(['travel_id' => $travel->id, 'price' => 100]);
        Tour::factory()->create(['travel_id' => $travel->id, 'price' => 150]);
        Tour::factory()->create(['travel_id' => $travel->id, 'price' => 200]);
        $endpoint = 'api/v1/travels/' . $travel->slug . '/tours';
        $response = $this->get($endpoint . '?priceFrom=150&priceTo=170');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price'=>'150.00']);
    }
}
