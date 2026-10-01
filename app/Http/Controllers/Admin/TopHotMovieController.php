<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TopHotMovie;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class TopHotMovieController extends Controller
{
    /**
     * Display the Top 10 Hot Movies management page.
     */
    public function index()
    {
        $topHotMovies = TopHotMovie::with('movie')->orderBy('rank', 'asc')->get();
        $allMovies = Movie::orderBy('title', 'asc')->get();

        // Check which movie IDs are currently in top 10
        $assignedMovieIds = $topHotMovies->pluck('movie_id')->toArray();

        return view('admin.top_movies.index', compact('topHotMovies', 'allMovies', 'assignedMovieIds'));
    }

    /**
     * Store a new movie in the Top 10 list.
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'rank' => 'required|integer|min:1|max:10',
            'sub_title' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:50',
            'badge_text_2' => 'nullable|string|max:50',
            'age_rating' => 'required|string|max:20',
            'custom_poster' => 'nullable|string',
            'custom_poster_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $customPosterUrl = $request->custom_poster;
        if ($request->hasFile('custom_poster_file')) {
            $file = $request->file('custom_poster_file');
            $filename = 'tophot_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/movies/tophot'), $filename);
            $customPosterUrl = '/uploads/movies/tophot/' . $filename;
        }

        // If this rank already exists, update it or shift other ranks
        $existingAtRank = TopHotMovie::where('rank', $request->rank)->first();
        if ($existingAtRank) {
            // Check if same movie is already elsewhere
            $sameMovie = TopHotMovie::where('movie_id', $request->movie_id)->first();
            if ($sameMovie) {
                // Swap ranks
                $oldRank = $sameMovie->rank;
                $sameMovie->update(['rank' => $request->rank]);
                $existingAtRank->update(['rank' => $oldRank]);
                return redirect()->route('admin.top_movies.index')->with('success', "Đã đổi thứ hạng giữa Top #{$request->rank} và Top #{$oldRank} thành công!");
            } else {
                // Replace existing at this rank or shift down
                $existingAtRank->update([
                    'movie_id' => $request->movie_id,
                    'sub_title' => $request->sub_title,
                    'badge_text' => $request->badge_text,
                    'badge_text_2' => $request->badge_text_2,
                    'age_rating' => $request->age_rating,
                    'custom_poster' => $customPosterUrl ?: $existingAtRank->custom_poster,
                    'is_active' => $request->boolean('is_active', true),
                ]);
                return redirect()->route('admin.top_movies.index')->with('success', "Đã cập nhật phim tại Top #{$request->rank}!");
            }
        }

        TopHotMovie::create([
            'movie_id' => $request->movie_id,
            'rank' => $request->rank,
            'sub_title' => $request->sub_title,
            'badge_text' => $request->badge_text,
            'badge_text_2' => $request->badge_text_2,
            'age_rating' => $request->age_rating,
            'custom_poster' => $customPosterUrl,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.top_movies.index')->with('success', "Đã thêm phim vào Top #{$request->rank} thành công!");
    }

    /**
     * Update an existing Top 10 item.
     */
    public function update(Request $request, TopHotMovie $topMovie)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'rank' => 'required|integer|min:1|max:10',
            'sub_title' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:50',
            'badge_text_2' => 'nullable|string|max:50',
            'age_rating' => 'required|string|max:20',
            'custom_poster' => 'nullable|string',
            'custom_poster_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $customPosterUrl = $request->custom_poster;
        if ($request->hasFile('custom_poster_file')) {
            $file = $request->file('custom_poster_file');
            $filename = 'tophot_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/movies/tophot'), $filename);
            $customPosterUrl = '/uploads/movies/tophot/' . $filename;
        }

        // If rank changed and conflicts with another
        if ($topMovie->rank != $request->rank) {
            $other = TopHotMovie::where('rank', $request->rank)->where('id', '!=', $topMovie->id)->first();
            if ($other) {
                // Swap rank
                $other->update(['rank' => $topMovie->rank]);
            }
        }

        $topMovie->update([
            'movie_id' => $request->movie_id,
            'rank' => $request->rank,
            'sub_title' => $request->sub_title,
            'badge_text' => $request->badge_text,
            'badge_text_2' => $request->badge_text_2,
            'age_rating' => $request->age_rating,
            'custom_poster' => $customPosterUrl ?: $topMovie->custom_poster,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('admin.top_movies.index')->with('success', "Cập nhật thông tin Top #{$topMovie->rank} thành công!");
    }

    /**
     * Remove movie from Top 10.
     */
    public function destroy(TopHotMovie $topMovie)
    {
        $rank = $topMovie->rank;
        $topMovie->delete();

        return redirect()->route('admin.top_movies.index')->with('success', "Đã gỡ phim khỏi Top #{$rank}!");
    }

    /**
     * Swap rank of a movie with adjacent (move up or down).
     */
    public function moveRank(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:top_hot_movies,id',
            'direction' => 'required|in:up,down',
        ]);

        $current = TopHotMovie::findOrFail($request->id);
        $currentRank = $current->rank;
        $targetRank = $request->direction === 'up' ? $currentRank - 1 : $currentRank + 1;

        if ($targetRank < 1 || $targetRank > 10) {
            return redirect()->route('admin.top_movies.index')->with('error', 'Không thể di chuyển vượt quá thứ hạng 1 đến 10!');
        }

        $adjacent = TopHotMovie::where('rank', $targetRank)->first();

        DB::transaction(function () use ($current, $adjacent, $currentRank, $targetRank) {
            if ($adjacent) {
                // Temporarily use 99 to avoid unique constraint if any
                $adjacent->update(['rank' => 99]);
                $current->update(['rank' => $targetRank]);
                $adjacent->update(['rank' => $currentRank]);
            } else {
                $current->update(['rank' => $targetRank]);
            }
        });

        return redirect()->route('admin.top_movies.index')->with('success', "Đã chuyển vị trí Top thành công!");
    }

    /**
     * Quick toggle active status.
     */
    public function toggleActive(TopHotMovie $topMovie)
    {
        $topMovie->is_active = !$topMovie->is_active;
        $topMovie->save();

        $status = $topMovie->is_active ? 'Hiển thị' : 'Ẩn';
        return redirect()->route('admin.top_movies.index')->with('success', "Đã đổi trạng thái Top #{$topMovie->rank} sang: {$status}!");
    }

    /**
     * Auto populate or reset Top 10 with latest/popular movies from database.
     */
    public function autoPopulate()
    {
        $seeder = new \Database\Seeders\TopHotMovieSeeder();
        $seeder->run();

        return redirect()->route('admin.top_movies.index')->with('success', 'Đã thiết lập tự động Top 10 Phim Hot thành công!');
    }
}
