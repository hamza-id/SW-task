<?php

namespace App\Http\Controllers\Api;

use App\Http\Service\External\StarWarApi;
use App\Http\Requests\Api\MovieUpdate;
use App\Http\Service\Api\MovieService;
use App\Http\Controllers\Controller;
use App\Helpers\ResponderHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;

class MovieController extends Controller
{

    protected $responder;
    public function __construct()
    {
        $this->responder = new ResponderHelper();
    }

    public function index(Request $request)
    {
        $data    = array();
        $error   = false;
        $message = 'Succesfully fetch records.';

        try {
            $title = $request->title ?? null;

            if ($title) {
                $movieService = new MovieService();
                $data         = $movieService->fetchFromTitle($title);
            } else {
                //when there is no query param then fetch new listing only
                $starWarApi     = new StarWarApi();
                $starWarsMovies = $starWarApi->fetchMovies();

                $movieService = new MovieService();
                $data         = $movieService->createOrFetchMovies($starWarsMovies);
            }
        } catch (Exception $e) {
            Log::error($e);
            $error   = true;
            $message = $e->getMessage();
        }
        return $this->responder->respond($error, $data, $message);
    }

    public function show($id)
    {
        $data    = array();
        $error   = false;
        $message = 'Succesfully fetch record.';

        try {
            $movieService = new MovieService();
            $movie        = $movieService->fetch($id, ['planets', 'starships', 'characters']);
            if ($movie->characters->count() == 0)
                $data = $movieService->updateRelatedData($movie);
            else
                $data = $movie;
        } catch (Exception $e) {
            Log::error($e);
            $error   = true;
            $message = $e->getMessage();
        }
        return $this->responder->respond($error, $data, $message);
    }

    public function update(MovieUpdate $request, $id)
    {
        $data    = array();
        $error   = false;
        $message = 'Succesfully fetch record.';

        try {
            $movieService = new MovieService();
            $data         = $movieService->update($id, $request);
        } catch (Exception $e) {
            Log::error($e);
            $error   = true;
            $message = $e->getMessage();
        }
        return $this->responder->respond($error, $data, $message);
    }

    public function destroy($id)
    {
        $data    = array();
        $error   = false;
        $message = 'The record is successfully deleted.';

        try {
            $movieService = new MovieService();
            $success      = $movieService->destroy($id);

            if (!$success)
                $message = 'The record has already been deleted or is not available.';
        } catch (Exception $e) {
            Log::error($e);
            $error   = true;
            $message = $e->getMessage();
        }
        return $this->responder->respond($error, $data, $message);
    }
}
