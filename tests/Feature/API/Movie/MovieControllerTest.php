<?php

namespace Tests\Api\Movie;

use Tests\TestCase;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_fetch_movies_from_title()
    {
        // Assuming there are movies in the database
        $response = $this->getJson('/api/movies?title=Star Wars');
        $response->assertStatus(200)
                 ->assertJson(['error' => false]);
        // You can add more assertions based on your expected response
    }

    /** @test */
    public function it_can_fetch_movies_when_no_title_provided()
    {
        // Assuming there are movies in the database
        $response = $this->getJson('/api/movies');
        $response->assertStatus(200)
                 ->assertJson(['error' => false]);
        // You can add more assertions based on your expected response
    }

    /** @test */
    public function it_can_show_movie_details()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->getJson('/api/movies/' . $movie->id);
        $response->assertStatus(200)
                 ->assertJson(['error' => false]);
        // You can add more assertions based on your expected response
    }

    /** @test */
    public function it_can_update_movie_details()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->putJson('/api/movies/' . $movie->id, [
            // Your update data
        ]);

        $response->assertStatus(200)
                 ->assertJson(['error' => false]);
        // You can add more assertions based on your expected response
    }

    /** @test */
    public function it_can_destroy_movie()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->deleteJson('/api/movies/' . $movie->id);
        $response->assertStatus(200)
                 ->assertJson(['error' => false]);
        // You can add more assertions based on your expected response
    }
}
