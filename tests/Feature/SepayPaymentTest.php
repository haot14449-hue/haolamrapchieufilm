<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class SepayPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Showtime $showtime;
    protected Seat $seat;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $this->user = User::factory()->create([
            'email' => 'haot14449@gmail.com',
            'points' => 0,
        ]);

        $cinema = Cinema::create(['name' => 'HCTV Cinema Hai Bà Trưng', 'location' => 'Hà Nội']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Phòng 01']);
        $movie = Movie::create(['title' => 'Avengers Endgame', 'duration' => 180, 'release_date' => now()]);
        
        $this->showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addDays(2),
            'price' => 100000,
        ]);

        $this->seat = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 1, 'type' => 'standard']);
    }

    public function test_sepay_webhook_marks_booking_as_paid()
    {
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'original_price' => 100000,
            'total_price' => 100000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(5),
        ]);

        Ticket::create([
            'booking_id' => $booking->id,
            'seat_id' => $this->seat->id,
            'status' => 'booked',
        ]);

        $payload = [
            'id' => 999123,
            'gateway' => 'MBBank',
            'transactionDate' => date('Y-m-d H:i:s'),
            'accountNumber' => '031205090305',
            'content' => 'HCTV' . $booking->id,
            'transferType' => 'in',
            'transferAmount' => 100000,
            'referenceCode' => 'MB_TEST_REF_' . uniqid(),
        ];

        $headers = [];
        if ($apiKey = config('services.sepay.api_key')) {
            $headers['Authorization'] = 'Apikey ' . $apiKey;
        }

        $response = $this->withHeaders($headers)->postJson('/api/sepay/webhook', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'paid',
            'booking_id' => $booking->id,
        ]);

        $booking->refresh();
        $this->assertEquals('paid', $booking->status);
        $this->assertEquals('MBBank (SePay QR)', $booking->payment_method);
    }

    public function test_pay_qr_screen_can_be_viewed_by_owner()
    {
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'original_price' => 100000,
            'total_price' => 100000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(5),
        ]);

        Ticket::create([
            'booking_id' => $booking->id,
            'seat_id' => $this->seat->id,
            'status' => 'booked',
        ]);

        $response = $this->actingAs($this->user)->get(route('booking.pay_qr', $booking->id));

        $response->assertStatus(200);
        $response->assertSee('031205090305');
        $response->assertSee('Trần Văn Hảo');
        $response->assertSee('HCTV' . $booking->id);

    }

    public function test_check_status_endpoint_returns_json()
    {
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'original_price' => 100000,
            'total_price' => 100000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(5),
        ]);

        $response = $this->actingAs($this->user)->getJson(route('booking.check_status', $booking->id));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $booking->id,
            'status' => 'pending',
            'is_paid' => false,
        ]);
    }

    public function test_simulate_qr_paid_marks_booking_as_paid()
    {
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'original_price' => 100000,
            'total_price' => 100000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(5),
        ]);

        Ticket::create([
            'booking_id' => $booking->id,
            'seat_id' => $this->seat->id,
            'status' => 'booked',
        ]);

        $response = $this->actingAs($this->user)->post(route('booking.simulate_qr_paid', $booking->id));

        $response->assertRedirect(route('booking.success', $booking->id));

        $booking->refresh();
        $this->assertEquals('paid', $booking->status);
    }
}
