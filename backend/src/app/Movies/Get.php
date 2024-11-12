<?php

namespace App\Movies;

use App\Core\Response;
use App\Core\Template;

class Get
{
    private $movies = [
        ['id' => 1, 'title' => 'Movie One', 'director' => 'Director One'],
        ['id' => 2, 'title' => 'Movie Two', 'director' => 'Director Two'],
        ['id' => 3, 'title' => 'Movie Three', 'director' => 'Director Three'],
    ];

    public function list()
    {
        // Bruk Template::render som håndterer feil hvis templaten mangler
        $htmlOutput = Template::render('components/movie_list.twig', ['movies' => $this->movies]);

        return Response::fromClientAccept([
            'json' => $this->movies,
            'html' => $htmlOutput
        ])->send();
    }

    public function get($id)
    {
        $movie = array_filter($this->movies, fn($m) => $m['id'] == $id);
        if (empty($movie)) {
            http_response_code(404);
            return Response::fromClientAccept([
                'json' => ['error' => 'Movie not found'],
                'html' => '<p>Movie not found</p>'
            ])->send();
        }
        $movie = array_values($movie)[0];
        $htmlOutput = Template::render('components/movie_item.twig', ['movie' => $movie]);

        return Response::fromClientAccept([
            'json' => $movie,
            'html' => $htmlOutput
        ])->send();
    }
}
