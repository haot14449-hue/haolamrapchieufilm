<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class HomeController extends Controller
{
    public function index()
    {
        $heroMovie = Movie::first();
        $movies = Movie::all();
        $genres = \App\Models\Genre::orderBy('name', 'asc')->get();
        return view('welcome', compact('heroMovie', 'movies', 'genres'));
    }
}
