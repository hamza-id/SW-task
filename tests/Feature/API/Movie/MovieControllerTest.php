<?php

namespace Tests\Api\Movie;

use App\Http\Controllers\Api\MovieController;
use App\Http\Service\Api\MovieService;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    public function test_index_with_title()
    {
        $request = $this->createRequest(['title' => 'A New Hope']);
        $movieServiceMock = $this->createMock(MovieService::class);
        $movieServiceMock->expects($this->once())->method('fetchFromTitle')
            ->with('A New Hope')->willReturn(['movie_data']);

        $controller = new MovieController();
        $response   = $controller->index($request);

        $response->assertStatus(200)
            ->assertExactJson([
                'code'    => 200,
                'message' => 'Successfully fetch records.'
            ]);
    }

    public function test_index_without_title()
    {
        $request = $this->createRequest();
        $movieServiceMock = $this->createMock(MovieService::class);
        $movieServiceMock->expects($this->once())
            ->method('createOrFetchMovies')
            ->with(['movies_data'])
            ->willReturn(['created_movies_data']);

        $controller = new MovieController();
        $response   = $controller->index($request);

        $response->assertStatus(200)
            ->assertExactJson([
                'code'    => 200,
                'message' => 'Successfully fetch records.'
            ]);
    }

    public function test_show()
    {
        $movieServiceMock = $this->createMock(MovieService::class);
        $movieServiceMock->expects($this->once())
            ->method('fetch')
            ->with(1, ['planets', 'starships', 'characters'])
            ->willReturn(['movie_data']);

        $controller = new MovieController();
        $response   = $controller->show(2);

        $response->assertStatus(200)
            ->assertExactJson([
                'code'    => 200,
                'message' => 'Successfully fetch record.'
            ]);
    }

    public function test_destroy_success()
    {
        $movieServiceMock = $this->createMock(MovieService::class);
        $movieServiceMock->expects($this->once())
            ->method('destroy')
            ->with(1)
            ->willReturn(true);

        $controller = new MovieController();
        $response = $controller->destroy(2);

        $response->assertStatus(200)
            ->assertExactJson([
                'code'    => 200,
                'message' => 'The record is successfully deleted.'
            ]);
    }

    private function createRequest($parameters = [])
    {
        return new \Illuminate\Http\Request($parameters);
    }
}