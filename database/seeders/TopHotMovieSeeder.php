<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\TopHotMovie;

class TopHotMovieSeeder extends Seeder
{
    public function run(): void
    {
        TopHotMovie::truncate();

        $curated = [
            [
                'title' => 'Mai',
                'sub_title' => 'A Film by Tran Thanh',
                'badge_text' => 'PD. 30',
                'badge_text_2' => 'TM. 16',
                'age_rating' => 'T18',
            ],
            [
                'title' => 'Avengers: Endgame',
                'sub_title' => 'Marvel Studios Avengers',
                'badge_text' => 'IMAX',
                'badge_text_2' => '3D',
                'age_rating' => 'T13',
            ],
            [
                'title' => 'Người Nhện: Không còn nhà',
                'sub_title' => 'No Way Home',
                'badge_text' => 'PD. 30',
                'badge_text_2' => 'TM. 16',
                'age_rating' => 'T13',
            ],
            [
                'title' => 'Dune: Hành Tinh Cát - Phần Hai',
                'sub_title' => 'Dune: Part Two',
                'badge_text' => 'PD. 24',
                'badge_text_2' => null,
                'age_rating' => 'T16',
            ],
            [
                'title' => 'Bố Già',
                'sub_title' => "Dad, I'm Sorry",
                'badge_text' => 'PD. 10',
                'badge_text_2' => null,
                'age_rating' => 'T13',
            ],
            [
                'title' => 'Oppenheimer',
                'sub_title' => 'A Film by Christopher Nolan',
                'badge_text' => 'PD. 38',
                'badge_text_2' => null,
                'age_rating' => 'T18',
            ],
            [
                'title' => 'Godzilla x Kong: Đế Chế Mới',
                'sub_title' => 'The New Empire',
                'badge_text' => 'PD. 8',
                'badge_text_2' => '3D',
                'age_rating' => 'T13',
            ],
            [
                'title' => 'Exhuma: Quật Mộ Trùng Ma',
                'sub_title' => 'Exhuma Mystery',
                'badge_text' => 'PD. 16',
                'badge_text_2' => null,
                'age_rating' => 'T16',
            ],
            [
                'title' => 'Lật Mặt',
                'sub_title' => 'Face Off Series',
                'badge_text' => 'PD. 8',
                'badge_text_2' => null,
                'age_rating' => 'T16',
            ],
            [
                'title' => 'Interstellar: Hố Đen Tử Thần',
                'sub_title' => 'Interstellar Space Journey',
                'badge_text' => 'IMAX 2D',
                'badge_text_2' => null,
                'age_rating' => 'T13',
            ],
        ];

        $rank = 1;
        foreach ($curated as $item) {
            $movie = Movie::where('title', 'like', '%' . $item['title'] . '%')->first();
            if ($movie) {
                TopHotMovie::create([
                    'movie_id' => $movie->id,
                    'rank' => $rank,
                    'sub_title' => $item['sub_title'],
                    'badge_text' => $item['badge_text'],
                    'badge_text_2' => $item['badge_text_2'],
                    'age_rating' => $item['age_rating'],
                    'is_active' => true,
                ]);
                $rank++;
            }
        }

        // If fewer than 10, fill with remaining movies
        if ($rank <= 10) {
            $existingIds = TopHotMovie::pluck('movie_id')->toArray();
            $remainingMovies = Movie::whereNotIn('id', $existingIds)->take(10 - $rank + 1)->get();
            foreach ($remainingMovies as $movie) {
                TopHotMovie::create([
                    'movie_id' => $movie->id,
                    'rank' => $rank,
                    'sub_title' => $movie->genre ?? 'Chiếu rạp',
                    'badge_text' => 'PD. 10',
                    'badge_text_2' => null,
                    'age_rating' => 'T13',
                    'is_active' => true,
                ]);
                $rank++;
            }
        }
    }
}
