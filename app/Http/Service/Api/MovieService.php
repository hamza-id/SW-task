<?php

namespace App\Http\Service\Api;

use App\Http\Service\External\StarWarApi;
use App\Models\{Character, Movie, Planet, Starship};

class MovieService
{
    public $model;
    public function __construct()
    {
        $this->model = new Movie();
    }

    public function fetch($id, $relations = array())
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    public function fetchFromTitle($title, $relations = array())
    {
        return $this->model->with($relations)->where('title', 'like', '%' . $title . '%')->get();
    }

    public function onlyFetch($id, $relations = array())
    {
        return $this->model->with($relations)->find($id);
    }

    public function destroy($id)
    {
        $model = $this->onlyFetch($id);
        if ($model) {
            $model->delete();
            return true;
        }
        return false;
    }

    public function createOrFetchMovies($movies, $title)
    {
        if ($movies) {
            foreach ($movies as $movie) {
                $this->model->updateOrCreate(
                    ['title' => $movie['title']],
                    [
                        'episode_id'    => $movie['episode_id'],
                        'opening_crawl' => $movie['opening_crawl'],
                        'director'      => $movie['director'],
                        'producer'      => $movie['producer'],
                        'release_date'  => $movie['release_date'],
                        'url'           => $movie['url'],
                    ]
                );
            }
        }
        return $movies;
    }

    public function updateRelatedData($movie)
    {
        $starWarApi = new StarWarApi();
        $movieData  = $starWarApi->fetchMovieById($movie);

        $planets = [];
        $planetsData = $starWarApi->fetchRelatedData($movieData['planets'], 'planets');
        foreach ($planetsData as $planetData) {
            $planets[] = Planet::updateOrCreate(['name' => $planetData['name']], $planetData);
        }
        $movie->planets()->saveMany($planets);

        $characters = [];
        $charactersData = $starWarApi->fetchRelatedData($movieData['characters'], 'characters');
        foreach ($charactersData as $characterData) {
            $characters[] = Character::updateOrCreate(['name' => $characterData['name']], $characterData);
        }
        $movie->characters()->saveMany($characters);

        $starships = [];
        $starshipsData = $starWarApi->fetchRelatedData($movieData['starships'], 'starships');
        foreach ($starshipsData as $starshipdata) {
            $starships[] = Starship::updateOrCreate(['name' => $starshipdata['name']], $starshipdata);
        }
        $movie->starships()->saveMany($starships);

        return $movie->refresh();
    }

    public function update($id, $request)
    {
        $movie = $this->fetch($id);
        $movie->fill($request->only([
            'title',
            'director',
            'release_date',
            'episode_id',
            'opening_crawl',
            'producer'
        ]));
        $movie->save();
    }
}
