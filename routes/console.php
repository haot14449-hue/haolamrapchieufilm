<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Booking;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('bookings:cleanup-expired', function () {
    $count = Booking::cleanupExpired();
    $this->info("Successfully cleaned up {$count} expired pending booking(s) and released seats.");
})->purpose('Clean up pending bookings that exceeded the 5-minute hold time and release seats');

Schedule::command('bookings:cleanup-expired')->everyMinute();

Artisan::command('db:transfer-sqlite-to-mysql', function () {
    $sqlitePath = database_path('database.sqlite');
    if (!file_exists($sqlitePath)) {
        $this->error("database.sqlite not found!");
        return;
    }

    $sqlite = new \PDO("sqlite:" . $sqlitePath);
    $sqlite->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $tables = [
        'users',
        'genres',
        'cinemas',
        'rooms',
        'seats',
        'movies',
        'movie_actors',
        'food',
        'promotions',
        'showtimes',
        'bookings',
        'booking_food',
        'tickets',
        'payments',
    ];

    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    foreach ($tables as $table) {
        try {
            $stmt = $sqlite->query("SELECT * FROM {$table}");
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            \Illuminate\Support\Facades\DB::table($table)->truncate();

            if (!empty($rows)) {
                foreach (array_chunk($rows, 500) as $chunk) {
                    \Illuminate\Support\Facades\DB::table($table)->insert($chunk);
                }
            }
            $this->info("Transferred " . count($rows) . " row(s) to table [{$table}]");
        } catch (\Throwable $e) {
            $this->warn("Skipping or error on table [{$table}]: " . $e->getMessage());
        }
    }

    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    $this->info("All data successfully transferred from SQLite to MySQL!");
})->purpose('Transfer all data from database.sqlite to MySQL database');
