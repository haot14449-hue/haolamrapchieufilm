<?php

namespace Tests\Feature;

use App\Mail\PasswordChangeOtpMail;
use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserProfileRewardPointsAndPasswordOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_profile_page()
    {
        $user = User::factory()->create([
            'name' => 'Nguyen Van A',
            'phone' => '0912345678',
            'points' => 50,
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertOk();
        $response->assertSee('Hồ Sơ Cá Nhân');
        $response->assertSee('Nguyen Van A');
        $response->assertSee('0912345678');
        $response->assertSee('50 Điểm');
    }

    public function test_user_can_update_personal_info_and_phone()
    {
        $user = User::factory()->create([
            'name' => 'Cu Name',
            'phone' => null,
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Moi Name',
            'phone' => '0987654321',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('profile_success');

        $user->refresh();
        $this->assertEquals('Moi Name', $user->name);
        $this->assertEquals('0987654321', $user->phone);
    }

    public function test_user_can_upload_avatar()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('my_avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'avatar' => $file,
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('profile_success');

        $user->refresh();
        $this->assertNotNull($user->avatar_url);
        $this->assertStringStartsWith('/uploads/avatars/', $user->avatar_url);

        $filePath = public_path(ltrim($user->avatar_url, '/'));
        $this->assertFileExists($filePath);

        // Cleanup
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    public function test_user_can_request_password_otp()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'member@example.com',
        ]);

        $response = $this->actingAs($user)->postJson(route('profile.password.send_otp'));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        Mail::assertSent(PasswordChangeOtpMail::class, function ($mail) use ($user) {
            return $mail->hasTo('member@example.com') && strlen($mail->otp) === 6;
        });

        $this->assertTrue(session()->has('password_change_otp'));
    }

    public function test_user_cannot_change_password_with_invalid_otp()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        session()->put('password_change_otp', [
            'code' => '123456',
            'email' => $user->email,
            'expires_at' => now()->addMinutes(10)->timestamp,
        ]);

        $response = $this->actingAs($user)->post(route('profile.password.update_otp'), [
            'otp' => '999999',
            'password' => 'newsecretpass123',
            'password_confirmation' => 'newsecretpass123',
        ]);

        $response->assertSessionHasErrors(['otp']);
        $this->assertTrue(Hash::check('oldpassword123', $user->fresh()->password));
    }

    public function test_user_can_change_password_with_valid_otp()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        session()->put('password_change_otp', [
            'code' => '888888',
            'email' => $user->email,
            'expires_at' => now()->addMinutes(10)->timestamp,
        ]);

        $response = $this->actingAs($user)->post(route('profile.password.update_otp'), [
            'otp' => '888888',
            'password' => 'newsecretpass123',
            'password_confirmation' => 'newsecretpass123',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('password_success');
        $this->assertFalse(session()->has('password_change_otp'));

        $this->assertTrue(Hash::check('newsecretpass123', $user->fresh()->password));
    }

    public function test_booking_checkout_and_points_discount()
    {
        $user = User::factory()->create(['points' => 30]); // 30 points = 30,000 VND
        $cinema = Cinema::create(['name' => 'HCTV Test', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Room 1']);
        $movie = Movie::create(['title' => 'Test Film', 'duration' => 120, 'release_date' => now()]);
        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addDay(),
            'price' => 100000,
        ]);
        $seat1 = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 1]);
        $seat2 = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 2]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'showtime_id' => $showtime->id,
            'total_price' => 200000,
            'original_price' => 200000,
            'status' => 'pending',
        ]);
        Ticket::create(['booking_id' => $booking->id, 'seat_id' => $seat1->id, 'status' => 'booked']);
        Ticket::create(['booking_id' => $booking->id, 'seat_id' => $seat2->id, 'status' => 'booked']);

        // 1. Checkout view displays user points
        $response = $this->actingAs($user)->get(route('booking.checkout', $booking->id));
        $response->assertOk();
        $response->assertSee('30');
        $response->assertSee('Điểm tích lũy HCTV');

        // 2. Apply 20 points (= 20,000 VND discount)
        $applyResponse = $this->actingAs($user)->post(route('booking.apply_points', $booking->id), [
            'points' => 20,
        ]);
        $applyResponse->assertRedirect();

        $booking->refresh();
        $this->assertEquals(20, $booking->points_used);
        $this->assertEquals(20000, $booking->discount_amount);
        $this->assertEquals(180000, $booking->total_price);

        // 3. Remove points
        $removeResponse = $this->actingAs($user)->post(route('booking.remove_points', $booking->id));
        $removeResponse->assertRedirect();
        $booking->refresh();
        $this->assertEquals(0, $booking->points_used);
        $this->assertEquals(0, $booking->discount_amount);
        $this->assertEquals(200000, $booking->total_price);

        // 4. Re-apply 10 points and test payment completion with reward points
        $this->actingAs($user)->post(route('booking.apply_points', $booking->id), ['points' => 10]);
        $booking->refresh();
        $this->assertEquals(10, $booking->points_used);

        // 5. Pay via Simulate
        Mail::fake();
        $payResponse = $this->actingAs($user)->post(route('booking.process_payment', $booking->id), [
            'simulate' => true,
        ]);
        $payResponse->assertRedirect(route('booking.success', $booking->id));

        $booking->refresh();
        $user->refresh();

        // 2 tickets = 20 points earned. 10 points used was deducted:
        // Initial 30 - 10 used + 20 earned = 40 points!
        $this->assertEquals('paid', $booking->status);
        $this->assertTrue($booking->points_processed);
        $this->assertEquals(20, $booking->points_earned);
        $this->assertEquals(40, $user->points);
    }

    public function test_booking_process_seats_sets_5_minute_hold_expires_at()
    {
        $user = User::factory()->create();
        $cinema = Cinema::create(['name' => 'HCTV Test', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Room 1']);
        $movie = Movie::create(['title' => 'Test Film', 'duration' => 120, 'release_date' => now()]);
        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addDay(),
            'price' => 100000,
        ]);
        $seat = Seat::create(['room_id' => $room->id, 'row' => 'B', 'number' => 1]);

        $response = $this->actingAs($user)->post(route('booking.process_seats', $showtime->id), [
            'seats' => [$seat->id],
        ]);

        $booking = Booking::where('user_id', $user->id)->where('showtime_id', $showtime->id)->first();
        $this->assertNotNull($booking);
        $response->assertRedirect(route('booking.food', $booking->id));

        $this->assertNotNull($booking->expires_at);
        $this->assertFalse($booking->isExpired());
        $this->assertGreaterThan(250, $booking->remaining_seconds);
        $this->assertLessThanOrEqual(300, $booking->remaining_seconds);
    }

    public function test_expired_pending_booking_releases_seats_to_available()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $cinema = Cinema::create(['name' => 'HCTV Test', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Room 1']);
        $movie = Movie::create(['title' => 'Test Film', 'duration' => 120, 'release_date' => now()]);
        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addDay(),
            'price' => 100000,
        ]);
        $seat = Seat::create(['room_id' => $room->id, 'row' => 'C', 'number' => 1]);

        // User1 creates a booking that expired 10 seconds ago
        $booking = Booking::create([
            'user_id' => $user1->id,
            'showtime_id' => $showtime->id,
            'total_price' => 100000,
            'status' => 'pending',
            'expires_at' => now()->subSeconds(10),
        ]);
        Ticket::create(['booking_id' => $booking->id, 'seat_id' => $seat->id, 'status' => 'booked']);

        // User 2 visits seats page - expired booking should be cleaned up automatically
        $response = $this->actingAs($user2)->get(route('booking.seats', $showtime->id));
        $response->assertOk();

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
        $this->assertEquals('cancelled', $booking->tickets()->first()->status);

        // Seat should be free for User 2 to select
        $processResponse = $this->actingAs($user2)->post(route('booking.process_seats', $showtime->id), [
            'seats' => [$seat->id],
        ]);
        $newBooking = Booking::where('user_id', $user2->id)->where('status', 'pending')->first();
        $this->assertNotNull($newBooking);
        $processResponse->assertRedirect(route('booking.food', $newBooking->id));
    }

    public function test_cannot_select_seat_held_by_another_user_within_5_minutes()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $cinema = Cinema::create(['name' => 'HCTV Test', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Room 1']);
        $movie = Movie::create(['title' => 'Test Film', 'duration' => 120, 'release_date' => now()]);
        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addDay(),
            'price' => 100000,
        ]);
        $seat = Seat::create(['room_id' => $room->id, 'row' => 'D', 'number' => 5]);

        // User 1 holds the seat (active 5 min hold)
        $this->actingAs($user1)->post(route('booking.process_seats', $showtime->id), [
            'seats' => [$seat->id],
        ]);

        // User 2 attempts to select the same seat
        $conflictResponse = $this->actingAs($user2)->post(route('booking.process_seats', $showtime->id), [
            'seats' => [$seat->id],
        ]);

        $conflictResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $user2->id,
            'status' => 'pending',
        ]);
    }

    public function test_accessing_expired_booking_at_checkout_redirects_to_seats_with_error()
    {
        $user = User::factory()->create();
        $cinema = Cinema::create(['name' => 'HCTV Test', 'location' => 'TP.HCM']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Room 1']);
        $movie = Movie::create(['title' => 'Test Film', 'duration' => 120, 'release_date' => now()]);
        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addDay(),
            'price' => 100000,
        ]);
        $seat = Seat::create(['room_id' => $room->id, 'row' => 'E', 'number' => 1]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'showtime_id' => $showtime->id,
            'total_price' => 100000,
            'status' => 'pending',
            'expires_at' => now()->subMinutes(1),
        ]);
        Ticket::create(['booking_id' => $booking->id, 'seat_id' => $seat->id, 'status' => 'booked']);

        $response = $this->actingAs($user)->get(route('booking.checkout', $booking->id));
        $response->assertRedirect(route('booking.seats', $showtime->id));
        $response->assertSessionHas('error');

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
    }
}
