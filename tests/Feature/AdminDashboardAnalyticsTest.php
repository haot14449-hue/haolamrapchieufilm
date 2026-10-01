<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_revenue_chart_and_movie_rankings()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $movieA = Movie::create([
            'title' => 'Phim Bom Tấn A',
            'duration' => 120,
            'genre' => 'Hành động',
            'poster_url' => 'https://example.com/a.jpg',
        ]);

        $movieB = Movie::create([
            'title' => 'Phim Hoạt Hình B',
            'duration' => 95,
            'genre' => 'Hoạt hình',
            'poster_url' => 'https://example.com/b.jpg',
        ]);

        $cinema = Cinema::create(['name' => 'HCTV Landmark', 'location' => 'Q1', 'city' => 'HCM']);
        $room = Room::create(['name' => 'Phòng 1', 'cinema_id' => $cinema->id, 'type' => '2D', 'total_seats' => 50]);

        $showtimeA = Showtime::create([
            'movie_id' => $movieA->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(2),
            'end_time' => now()->addHours(4),
            'price' => 100000,
        ]);

        $showtimeB = Showtime::create([
            'movie_id' => $movieB->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(5),
            'end_time' => now()->addHours(7),
            'price' => 80000,
        ]);

        // Booking 1 for Movie A: 3 tickets, 300k
        $booking1 = Booking::create([
            'user_id' => $customer->id,
            'showtime_id' => $showtimeA->id,
            'original_price' => 300000,
            'total_price' => 300000,
            'status' => 'paid',
        ]);
        for ($i = 1; $i <= 3; $i++) {
            $seat = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => $i, 'type' => 'standard']);
            Ticket::create(['booking_id' => $booking1->id, 'seat_id' => $seat->id, 'status' => 'paid']);
        }

        // Booking 2 for Movie B: 1 ticket, 80k
        $booking2 = Booking::create([
            'user_id' => $customer->id,
            'showtime_id' => $showtimeB->id,
            'original_price' => 80000,
            'total_price' => 80000,
            'status' => 'paid',
        ]);
        $seatB = Seat::create(['room_id' => $room->id, 'row' => 'B', 'number' => 1, 'type' => 'standard']);
        Ticket::create(['booking_id' => $booking2->id, 'seat_id' => $seatB->id, 'status' => 'paid']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Thống Kê Doanh Thu Theo Ngày');
        $response->assertSee('Phim Bán Chạy Nhất');
        $response->assertSee('dailyRevenueChart');
        $response->assertSee('Phim Bom Tấn A');
        $response->assertSee('3 vé');
        $response->assertSee('Phim Hoạt Hình B');
        $response->assertSee('1 vé');
        $response->assertSee('380.000 đ');
    }
}
