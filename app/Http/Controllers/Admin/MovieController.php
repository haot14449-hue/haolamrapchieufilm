<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\MovieActor;

use App\Models\Genre;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::withCount('actors')->with('topHot')->orderBy('created_at', 'desc')->get();
        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        $genres = Genre::orderBy('name', 'asc')->get();
        return view('admin.movies.form', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'backdrop_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'poster_url' => 'nullable|string',
            'backdrop_url' => 'nullable|string',
            'trailer_url' => 'required|string',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'genres' => 'nullable|array',
            'custom_genre' => 'nullable|string|max:255',
        ]);

        if (!$request->hasFile('poster_file') && !$request->filled('poster_url')) {
            return back()->withInput()->withErrors(['poster_url' => 'Vui lòng tải ảnh Poster từ máy tính hoặc nhập Link ảnh!']);
        }

        if (!$request->hasFile('backdrop_file') && !$request->filled('backdrop_url')) {
            return back()->withInput()->withErrors(['backdrop_url' => 'Vui lòng tải ảnh Backdrop từ máy tính hoặc nhập Link ảnh!']);
        }

        $movie = new Movie();
        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->trailer_url = $request->trailer_url;
        $movie->duration = $request->duration;
        $movie->release_date = $request->release_date;

        // Process Genre (combine checkboxes and custom input)
        $movie->genre = $this->formatGenres($request);

        // Process Poster Upload / URL
        if ($request->hasFile('poster_file')) {
            $file = $request->file('poster_file');
            $filename = 'poster_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/movies/posters'), $filename);
            $movie->poster_url = '/uploads/movies/posters/' . $filename;
        } else {
            $movie->poster_url = $request->poster_url;
        }

        // Process Backdrop Upload / URL
        if ($request->hasFile('backdrop_file')) {
            $file = $request->file('backdrop_file');
            $filename = 'backdrop_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/movies/backdrops'), $filename);
            $movie->backdrop_url = '/uploads/movies/backdrops/' . $filename;
        } else {
            $movie->backdrop_url = $request->backdrop_url;
        }

        $movie->save();

        // Process Actors
        $this->saveActors($request, $movie);

        // Process Top 10 Phim Hot
        $this->saveTopHot($request, $movie);

        return redirect()->route('admin.movies.index')->with('success', 'Thêm phim mới thành công!');
    }

    public function edit(Movie $movie)
    {
        $movie->load(['actors', 'topHot']);
        $genres = Genre::orderBy('name', 'asc')->get();
        return view('admin.movies.form', compact('movie', 'genres'));
    }

    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'backdrop_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'poster_url' => 'nullable|string',
            'backdrop_url' => 'nullable|string',
            'trailer_url' => 'required|string',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'genres' => 'nullable|array',
            'custom_genre' => 'nullable|string|max:255',
        ]);

        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->trailer_url = $request->trailer_url;
        $movie->duration = $request->duration;
        $movie->release_date = $request->release_date;

        // Process Genre
        $movie->genre = $this->formatGenres($request);

        // Process Poster
        if ($request->hasFile('poster_file')) {
            $file = $request->file('poster_file');
            $filename = 'poster_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/movies/posters'), $filename);
            $movie->poster_url = '/uploads/movies/posters/' . $filename;
        } elseif ($request->filled('poster_url')) {
            $movie->poster_url = $request->poster_url;
        }

        // Process Backdrop
        if ($request->hasFile('backdrop_file')) {
            $file = $request->file('backdrop_file');
            $filename = 'backdrop_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/movies/backdrops'), $filename);
            $movie->backdrop_url = '/uploads/movies/backdrops/' . $filename;
        } elseif ($request->filled('backdrop_url')) {
            $movie->backdrop_url = $request->backdrop_url;
        }

        $movie->save();

        // Process Actors
        $this->saveActors($request, $movie);

        // Process Top 10 Phim Hot
        $this->saveTopHot($request, $movie);

        return redirect()->route('admin.movies.index')->with('success', 'Cập nhật phim thành công!');
    }

    public function destroy(Movie $movie)
    {
        if ($movie->showtimes()->count() > 0) {
            return redirect()->route('admin.movies.index')->with('error', 'Không thể xóa phim đang có suất chiếu!');
        }

        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Xóa phim thành công!');
    }

    /**
     * Combine genres from checkboxes and custom input into a single string.
     */
    private function formatGenres(Request $request): string
    {
        $genres = $request->input('genres', []);
        if (!is_array($genres)) {
            $genres = [];
        }

        if ($request->filled('custom_genre')) {
            $customItems = array_map('trim', explode(',', $request->input('custom_genre')));
            $genres = array_merge($genres, $customItems);
        }

        $cleanGenres = array_values(array_unique(array_filter(array_map('trim', $genres))));

        if (!empty($cleanGenres)) {
            return implode(', ', $cleanGenres);
        }

        return $request->input('genre', 'Chung');
    }

    /**
     * Save or update actors for a movie.
     */
    private function saveActors(Request $request, Movie $movie): void
    {
        $actorsData = $request->input('actors', []);
        $actorFiles = $request->file('actors', []);
        $retainedIds = [];

        if (is_array($actorsData)) {
            foreach ($actorsData as $index => $data) {
                if (empty($data['name'])) {
                    continue;
                }

                $actorId = $data['id'] ?? null;
                $actor = null;
                if ($actorId) {
                    $actor = $movie->actors()->find($actorId);
                }
                if (!$actor) {
                    $actor = new MovieActor();
                    $actor->movie_id = $movie->id;
                }

                $actor->name = trim($data['name']);
                $actor->role = !empty($data['role']) ? trim($data['role']) : null;
                $actor->bio = !empty($data['bio']) ? trim($data['bio']) : null;
                $actor->order = (int) $index;

                // Handle file upload for actor avatar
                if (isset($actorFiles[$index]['avatar_file']) && $actorFiles[$index]['avatar_file']->isValid()) {
                    $file = $actorFiles[$index]['avatar_file'];
                    $filename = 'actor_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/actors'), $filename);
                    $actor->avatar = '/uploads/actors/' . $filename;
                } elseif (!empty($data['avatar_url'])) {
                    $actor->avatar = trim($data['avatar_url']);
                }

                $actor->save();
                $retainedIds[] = $actor->id;
            }
        }

        // Remove actors that were removed from the form
        $movie->actors()->whereNotIn('id', $retainedIds)->delete();
    }

    /**
     * Save or sync Top 10 Hot status for movie.
     */
    private function saveTopHot(Request $request, Movie $movie): void
    {
        if ($request->boolean('is_in_top_hot')) {
            $rank = (int) $request->input('top_hot_rank', 1);
            \App\Models\TopHotMovie::updateOrCreate(
                ['movie_id' => $movie->id],
                [
                    'rank' => $rank,
                    'sub_title' => $request->input('top_hot_sub_title'),
                    'badge_text' => $request->input('top_hot_badge_text'),
                    'badge_text_2' => $request->input('top_hot_badge_text_2'),
                    'age_rating' => $request->input('top_hot_age_rating', 'T13'),
                    'is_active' => true,
                ]
            );
        } elseif ($request->has('top_hot_submitted')) {
            \App\Models\TopHotMovie::where('movie_id', $movie->id)->delete();
        }
    }
}
