<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\MovieActor;
use App\Models\Genre;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $genre = trim($request->input('genre', ''));

        $query = Movie::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('actors', function ($aq) use ($search) {
                      $aq->where('name', 'like', "%{$search}%")
                         ->orWhere('role', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($genre)) {
            $query->where('genre', 'like', "%{$genre}%");
        }

        $movies = $query->orderBy('created_at', 'desc')->get();
        $genres = Genre::orderBy('name', 'asc')->get();

        return view('movies.index', compact('movies', 'genres', 'search', 'genre'));
    }

    public function show($id)
    {
        $movie = Movie::with('actors')->findOrFail($id);
        return view('movies.show', compact('movie'));
    }

    /**
     * API for live search suggestions (Movies and Actors).
     */
    public function suggest(Request $request)
    {
        $q = trim($request->input('q', ''));
        $genre = trim($request->input('genre', ''));

        if (empty($q) && empty($genre)) {
            return response()->json([
                'movies' => [],
                'actors' => [],
            ]);
        }

        // Query Movies
        $movieQuery = Movie::query();

        if (!empty($genre)) {
            $movieQuery->where('genre', 'like', "%{$genre}%");
        }

        if (!empty($q)) {
            $movieQuery->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhereHas('actors', function ($aq) use ($q) {
                          $aq->where('name', 'like', "%{$q}%")
                             ->orWhere('role', 'like', "%{$q}%");
                      });
            });
        }

        $movies = $movieQuery->orderBy('created_at', 'desc')->limit(5)->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'title' => $m->title,
                'poster_url' => $m->poster_url,
                'genre' => $m->genre,
                'duration' => $m->duration,
                'year' => $m->release_date ? date('Y', strtotime($m->release_date)) : '',
                'url' => route('movies.show', $m->id),
            ];
        });

        // Query Actors (only if search keyword is provided)
        $actors = collect();
        if (!empty($q)) {
            $actorQuery = MovieActor::with('movie')
                ->where(function ($aq) use ($q) {
                    $aq->where('name', 'like', "%{$q}%")
                       ->orWhere('role', 'like', "%{$q}%");
                });

            if (!empty($genre)) {
                $actorQuery->whereHas('movie', function ($mq) use ($genre) {
                    $mq->where('genre', 'like', "%{$genre}%");
                });
            }

            $actors = $actorQuery->limit(4)->get()->map(function ($a) {
                return [
                    'id' => $a->id,
                    'name' => $a->name,
                    'role' => $a->role,
                    'avatar_url' => $a->avatar_url,
                    'movie_id' => $a->movie_id,
                    'movie_title' => $a->movie ? $a->movie->title : '',
                    'movie_url' => $a->movie ? route('movies.show', $a->movie->id) : '',
                ];
            });
        }

        return response()->json([
            'movies' => $movies,
            'actors' => $actors,
        ]);
    }
}
