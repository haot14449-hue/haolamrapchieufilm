<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\TopHotMovie;
use App\Models\Genre;

class HomeController extends Controller
{
    public function index()
    {
        $heroMovie = Movie::first();
        $movies = Movie::all();
        $genres = Genre::orderBy('name', 'asc')->get();

        // Lấy Top 10 Phim Hot đã được Admin cấu hình
        $topHotMovies = TopHotMovie::with('movie')
            ->where('is_active', true)
            ->whereHas('movie')
            ->orderBy('rank', 'asc')
            ->take(10)
            ->get();

        // Tự động fallback nếu chưa có dữ liệu cấu hình
        if ($topHotMovies->isEmpty() && $movies->isNotEmpty()) {
            $rank = 1;
            $topHotMovies = $movies->take(10)->map(function ($m) use (&$rank) {
                $obj = new TopHotMovie([
                    'movie_id' => $m->id,
                    'rank' => $rank++,
                    'sub_title' => $m->genre,
                    'badge_text' => 'PD. 10',
                    'age_rating' => 'T13',
                    'is_active' => true,
                ]);
                $obj->setRelation('movie', $m);
                return $obj;
            });
        }

        return view('welcome', compact('heroMovie', 'movies', 'genres', 'topHotMovies'));
    }
}
