<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@hctv.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@hctv.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $movieId1 = \DB::table('movies')->insertGetId([
            'title' => 'DUNE: PART TWO',
            'description' => 'Paul Atreides unites with Chani and the Fremen while on a warpath of revenge against the conspirators who destroyed his family.',
            'poster_url' => 'https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2JGjjc9CW.jpg',
            'backdrop_url' => 'https://image.tmdb.org/t/p/w1280/8rpDcsfLJypbO6vtecsmEZf5ZAe.jpg',
            'trailer_url' => '#',
            'duration' => 166,
            'release_date' => '2024-02-27',
            'genre' => 'Sci-Fi / Action',
        ]);

        $movieId2 = \DB::table('movies')->insertGetId([
            'title' => 'OPPENHEIMER',
            'description' => 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.',
            'poster_url' => 'https://image.tmdb.org/t/p/w500/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg',
            'backdrop_url' => 'https://image.tmdb.org/t/p/w1280/fm6KqXpk3M2HVveHwCrBSSBaO0V.jpg',
            'trailer_url' => '#',
            'duration' => 180,
            'release_date' => '2023-07-19',
            'genre' => 'Drama / History',
        ]);
        
        $movieId3 = \DB::table('movies')->insertGetId([
            'title' => 'THE BATMAN',
            'description' => 'When a sadistic serial killer begins murdering key political figures in Gotham, Batman is forced to investigate.',
            'poster_url' => 'https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg',
            'backdrop_url' => 'https://image.tmdb.org/t/p/w1280/b0PlSFdSmBgZAqfkI5ZzI9Xl45d.jpg',
            'trailer_url' => '#',
            'duration' => 176,
            'release_date' => '2022-03-01',
            'genre' => 'Action / Crime',
        ]);

        $cinemaId = \DB::table('cinemas')->insertGetId([
            'name' => 'HCTV Cinematic',
            'location' => 'Ho Chi Minh City',
        ]);

        $roomId = \DB::table('rooms')->insertGetId([
            'cinema_id' => $cinemaId,
            'name' => 'IMAX 1',
            'capacity' => 100,
        ]);

        \DB::table('showtimes')->insert([
            ['movie_id' => $movieId1, 'room_id' => $roomId, 'start_time' => now()->addDays(1)->format('Y-m-d 18:00:00'), 'price' => 150000],
            ['movie_id' => $movieId1, 'room_id' => $roomId, 'start_time' => now()->addDays(1)->format('Y-m-d 21:00:00'), 'price' => 150000],
            ['movie_id' => $movieId2, 'room_id' => $roomId, 'start_time' => now()->addDays(2)->format('Y-m-d 19:30:00'), 'price' => 120000],
            ['movie_id' => $movieId3, 'room_id' => $roomId, 'start_time' => now()->addDays(3)->format('Y-m-d 20:00:00'), 'price' => 130000],
        ]);

        // Create Seats for Room 1
        $rows = ['A', 'B', 'C', 'D', 'E'];
        $seatsData = [];
        foreach ($rows as $row) {
            for ($i = 1; $i <= 10; $i++) {
                $seatsData[] = [
                    'room_id' => $roomId,
                    'row' => $row,
                    'number' => $i,
                ];
            }
        }
        \DB::table('seats')->insert($seatsData);

        // Create Foods
        \DB::table('food')->insert([
            ['name' => 'Bắp Ngọt Lớn', 'description' => 'Bắp rang bơ vị ngọt size L', 'price' => 69000, 'image_url' => 'https://images.unsplash.com/photo-1572177191856-3cde618dee1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60'],
            ['name' => 'Bắp Phô Mai Lớn', 'description' => 'Bắp rang bơ vị phô mai size L', 'price' => 79000, 'image_url' => 'https://images.unsplash.com/photo-1585670146399-5287f3944fb4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60'],
            ['name' => 'Combo Couple', 'description' => '2 Nước ngọt lớn + 1 Bắp lớn', 'price' => 120000, 'image_url' => 'https://images.unsplash.com/photo-1596662951482-0c4ba74a6df6?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60'],
        ]);

        // Create Promotions
        \DB::table('promotions')->insert([
            ['code' => 'WELCOME2026', 'title' => 'Giảm 20% cho thành viên mới', 'description' => 'Áp dụng cho lần mua vé đầu tiên', 'discount_percent' => 20, 'discount_amount' => null, 'points_required' => 0, 'start_date' => now(), 'end_date' => now()->addMonths(1), 'image_url' => 'https://images.unsplash.com/photo-1542204165-65bf26472b9b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60'],
            ['code' => 'POINT500', 'title' => 'Đổi 500 điểm lấy voucher 50k', 'description' => 'Dành cho khách hàng thân thiết', 'discount_percent' => null, 'discount_amount' => 50000, 'points_required' => 500, 'start_date' => now(), 'end_date' => now()->addMonths(6), 'image_url' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60'],
        ]);

        // Seed Genres & Actors & More Movies
        $this->call(GenreSeeder::class);
        $this->call(ActorSeeder::class);
        $this->call(MoreMoviesSeeder::class);
    }
}
