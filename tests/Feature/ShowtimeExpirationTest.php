<?php

namespace Tests\Feature;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowtimeExpirationTest extends TestCase
{
    use RefreshDatabase;

    public function test_past_showtimes_do_not_appear_on_frontend_showtimes_page()
    {
        $cinema = Cinema::create(['name' => 'HCTV Test Cinema', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Phòng 1']);
        $movie = Movie::create(['title' => 'Film Sắp Chiếu', 'duration' => 120, 'release_date' => now()]);

        // Past showtime (started 2 hours ago)
        $pastShowtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->subHours(2),
            'price' => 90000,
        ]);

        // Future showtime (starts in 3 hours)
        $futureShowtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(3),
            'price' => 100000,
        ]);

        $response = $this->get(route('showtimes'));
        $response->assertOk();
        $response->assertSee('Film Sắp Chiếu');

        // Future showtime must be visible
        $futureHour = \Carbon\Carbon::parse($futureShowtime->start_time)->format('H:i');
        $response->assertSee($futureHour);
        $response->assertSee(route('booking.seats', $futureShowtime->id));

        // Past showtime must NOT be visible / must disappear automatically
        $response->assertDontSee(route('booking.seats', $pastShowtime->id));
    }

    public function test_movie_with_only_past_showtimes_does_not_appear_on_showtimes_page()
    {
        $cinema = Cinema::create(['name' => 'HCTV Test Cinema', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Phòng 1']);
        
        $pastMovie = Movie::create(['title' => 'Phim Cũ Đã Chiếu Xong', 'duration' => 120, 'release_date' => now()->subDays(5)]);
        Showtime::create([
            'movie_id' => $pastMovie->id,
            'room_id' => $room->id,
            'start_time' => now()->subDay(),
            'price' => 80000,
        ]);

        $response = $this->get(route('showtimes'));
        $response->assertOk();
        $response->assertDontSee('Phim Cũ Đã Chiếu Xong');
    }

    public function test_user_cannot_access_seats_page_for_past_showtime()
    {
        $user = User::factory()->create();
        $cinema = Cinema::create(['name' => 'HCTV Test Cinema', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Phòng 1']);
        $movie = Movie::create(['title' => 'Phim Hết Giờ', 'duration' => 120, 'release_date' => now()]);

        $pastShowtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->subMinutes(30),
            'price' => 90000,
        ]);

        $response = $this->actingAs($user)->get(route('booking.seats', $pastShowtime->id));
        $response->assertRedirect(route('showtimes'));
        $response->assertSessionHas('error');
    }

    public function test_user_cannot_submit_process_seats_for_past_showtime()
    {
        $user = User::factory()->create();
        $cinema = Cinema::create(['name' => 'HCTV Test Cinema', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Phòng 1']);
        $movie = Movie::create(['title' => 'Phim Hết Giờ', 'duration' => 120, 'release_date' => now()]);
        $seat = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 1]);

        $pastShowtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->subMinutes(15),
            'price' => 90000,
        ]);

        $response = $this->actingAs($user)->post(route('booking.process_seats', $pastShowtime->id), [
            'seats' => [$seat->id],
        ]);

        $response->assertRedirect(route('showtimes'));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', [
            'showtime_id' => $pastShowtime->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_showtime_model_helpers_and_scopes()
    {
        $cinema = Cinema::create(['name' => 'HCTV Test Cinema', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Phòng 1']);
        $movie = Movie::create(['title' => 'Phim Test Helper', 'duration' => 120, 'release_date' => now()]);

        $pastShowtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->subMinutes(10),
            'price' => 90000,
        ]);

        $futureShowtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addMinutes(10),
            'price' => 90000,
        ]);

        $this->assertTrue($pastShowtime->isPast());
        $this->assertFalse($pastShowtime->isUpcoming());

        $this->assertFalse($futureShowtime->isPast());
        $this->assertTrue($futureShowtime->isUpcoming());

        $this->assertCount(1, Showtime::upcoming()->get());
        $this->assertCount(1, Showtime::past()->get());
        $this->assertCount(1, $movie->upcomingShowtimes()->get());
    }

    public function test_admin_can_filter_showtimes_by_time_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cinema = Cinema::create(['name' => 'HCTV Test Cinema', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Phòng 1']);
        $movie = Movie::create(['title' => 'Phim Test Admin', 'duration' => 120, 'release_date' => now()]);

        $past = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->subDay(),
            'price' => 90000,
        ]);

        $future = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addDay(),
            'price' => 90000,
        ]);

        // Filter upcoming
        $upcomingResponse = $this->actingAs($admin)->get(route('admin.showtimes.index', ['time_status' => 'upcoming']));
        $upcomingResponse->assertOk();
        $upcomingResponse->assertSee(route('admin.showtimes.edit', $future->id));
        $upcomingResponse->assertDontSee(route('admin.showtimes.edit', $past->id));

        // Filter past
        $pastResponse = $this->actingAs($admin)->get(route('admin.showtimes.index', ['time_status' => 'past']));
        $pastResponse->assertOk();
        $pastResponse->assertSee(route('admin.showtimes.edit', $past->id));
        $pastResponse->assertDontSee(route('admin.showtimes.edit', $future->id));
    }
}
