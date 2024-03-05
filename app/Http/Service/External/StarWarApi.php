<?php

namespace App\Http\Service\External;

use Illuminate\Support\Facades\{Cache, Http, Log};
use Illuminate\Http\Client\RequestException;
use Exception;

class StarWarApi
{
    protected $baseUrl, $timeout;
    public function __construct()
    {
        $this->baseUrl     = 'https://swapi.dev/api/';
        $this->timeout = 60;
    }

    public function fetchMovies()
    {
        $data = array();
        try {
            $cacheKey  = 'star_wars_films';
            $cacheTime = config('constants.star_war_films_cache_time');

            $url  = $this->baseUrl . 'films';
            $data = Cache::remember($cacheKey, $cacheTime, function () use ($url) {
                $response = Http::timeout($this->timeout)->get($url);
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['results'])) {
                        return $data['results'];
                    }
                }
            });
        } catch (RequestException $e) {
            Log::error($e);
        } catch (Exception $e) {
            Log::error($e);
        }
        return $data;
    }

    public function fetchMovieById($movie)
    {
        $data = array();
        try {
            $cacheKey  = 'star_wars_films_' . $movie->episode_id;
            $cacheTime = config('constants.star_war_films_cache_time');

            $url  = $movie->url;
            $data = Cache::remember($cacheKey, $cacheTime, function () use ($url) {
                $response = Http::timeout($this->timeout)->get($url);
                if ($response->successful()) {
                    return $response->json();
                }
            });
        } catch (RequestException $e) {
            Log::error($e);
        } catch (Exception $e) {
            Log::error($e);
        }
        return $data;
    }

    public function fetchRelatedData($urls)
    {
        $data      = array();
        $cacheTime = config('constants.star_war_films_cache_time');

        foreach ($urls as $url) {
            $urlExplode = explode('/', $url);
            $type = $urlExplode[count($urlExplode) - 3];
            $id   = $urlExplode[count($urlExplode) - 2];
            $cacheKey  = 'star_wars_' . $type . '_' . $id;

            $data[] = Cache::remember($cacheKey, $cacheTime, function () use ($url) {
                $response = Http::timeout($this->timeout)->get($url);
                if ($response->successful()) {
                    return $response->json();
                }
            });
            if (count($data) == 5)   //only five item of each type
                break;
        }
        return $data;
    }
}
