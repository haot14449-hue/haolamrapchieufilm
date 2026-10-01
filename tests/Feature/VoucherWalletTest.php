<?php

namespace Tests\Feature;

use App\Models\Promotion;
use App\Models\User;
use App\Models\UserVoucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoucherWalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_voucher_wallet()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('account.vouchers'));

        $response->assertStatus(200);
        $response->assertSee('Ví Voucher Của Tôi');
        $response->assertSee('Kho ưu đãi của Web');
    }

    public function test_user_can_save_voucher_to_wallet()
    {
        $user = User::factory()->create();
        $promo = Promotion::firstOrCreate(
            ['code' => 'TESTVOUCHER100'],
            [
                'title' => 'Test Voucher 100k',
                'description' => 'Mô tả voucher',
                'discount_amount' => 100000,
                'points_required' => 0,
                'end_date' => now()->addMonth(),
            ]
        );

        $response = $this->actingAs($user)->post(route('account.vouchers.save'), [
            'promotion_id' => $promo->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_vouchers', [
            'user_id' => $user->id,
            'promotion_id' => $promo->id,
            'is_used' => false,
        ]);
    }

    public function test_user_can_save_voucher_by_code()
    {
        $user = User::factory()->create();
        $promo = Promotion::firstOrCreate(
            ['code' => 'CODEPROMO20'],
            [
                'title' => 'Test Promo 20%',
                'description' => 'Mô tả promo 20%',
                'discount_percent' => 20,
                'points_required' => 0,
                'end_date' => now()->addMonth(),
            ]
        );

        $response = $this->actingAs($user)->post(route('account.vouchers.save'), [
            'code' => 'CODEPROMO20',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_vouchers', [
            'user_id' => $user->id,
            'promotion_id' => $promo->id,
        ]);
    }

    public function test_user_can_remove_voucher_from_wallet()
    {
        $user = User::factory()->create();
        $promo = Promotion::firstOrCreate(
            ['code' => 'DELVOUCHER50'],
            [
                'title' => 'Voucher to delete',
                'discount_amount' => 50000,
                'end_date' => now()->addMonth(),
            ]
        );

        $uv = UserVoucher::create([
            'user_id' => $user->id,
            'promotion_id' => $promo->id,
            'is_used' => false,
        ]);

        $response = $this->actingAs($user)->delete(route('account.vouchers.remove', $uv->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('user_vouchers', [
            'id' => $uv->id,
        ]);
    }

    public function test_checkout_displays_available_promotions_and_user_vouchers()
    {
        $user = User::factory()->create();
        $cinema = \App\Models\Cinema::create(['name' => 'Test Cinema', 'location' => '123 Test', 'city' => 'HCM']);
        $room = \App\Models\Room::create(['name' => 'Room 1', 'cinema_id' => $cinema->id, 'type' => '2D', 'total_seats' => 50]);
        $movie = \App\Models\Movie::create(['title' => 'Test Movie', 'duration' => 120, 'release_date' => now(), 'status' => 'now_showing']);
        $showtime = \App\Models\Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(2),
            'end_time' => now()->addHours(4),
            'price' => 100000,
        ]);
        $seat = \App\Models\Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 1, 'type' => 'standard']);
        $booking = \App\Models\Booking::create([
            'user_id' => $user->id,
            'showtime_id' => $showtime->id,
            'original_price' => 100000,
            'total_price' => 100000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(5),
        ]);
        \App\Models\Ticket::create(['booking_id' => $booking->id, 'seat_id' => $seat->id, 'status' => 'reserved']);

        $promo = Promotion::firstOrCreate(
            ['code' => 'CHECKOUTPROMO'],
            [
                'title' => 'Checkout Promo Title',
                'discount_amount' => 30000,
                'end_date' => now()->addMonth(),
            ]
        );

        $response = $this->actingAs($user)->get(route('booking.checkout', $booking->id));

        $response->assertStatus(200);
        $response->assertSee('Mã Voucher');
        $response->assertSee('CHECKOUTPROMO');
        $response->assertSee('Checkout Promo Title');
    }

    public function test_user_can_apply_and_remove_voucher_on_checkout()
    {
        $user = User::factory()->create();
        $cinema = \App\Models\Cinema::create(['name' => 'Test Cinema 2', 'location' => '123 Test', 'city' => 'HCM']);
        $room = \App\Models\Room::create(['name' => 'Room 2', 'cinema_id' => $cinema->id, 'type' => '2D', 'total_seats' => 50]);
        $movie = \App\Models\Movie::create(['title' => 'Test Movie 2', 'duration' => 120, 'release_date' => now(), 'status' => 'now_showing']);
        $showtime = \App\Models\Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(2),
            'end_time' => now()->addHours(4),
            'price' => 100000,
        ]);
        $seat = \App\Models\Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 2, 'type' => 'standard']);
        $booking = \App\Models\Booking::create([
            'user_id' => $user->id,
            'showtime_id' => $showtime->id,
            'original_price' => 100000,
            'total_price' => 100000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(5),
        ]);
        \App\Models\Ticket::create(['booking_id' => $booking->id, 'seat_id' => $seat->id, 'status' => 'reserved']);

        $promo = Promotion::firstOrCreate(
            ['code' => 'APPLYNOW25'],
            [
                'title' => 'Apply 25K Promo',
                'discount_amount' => 25000,
                'end_date' => now()->addMonth(),
            ]
        );

        // Apply voucher
        $response = $this->actingAs($user)->post(route('booking.apply_voucher', $booking->id), [
            'code' => 'APPLYNOW25',
        ]);

        $response->assertRedirect();
        $booking->refresh();
        $this->assertEquals($promo->id, $booking->promotion_id);
        $this->assertEquals(25000, $booking->discount_amount);
        $this->assertEquals(75000, $booking->total_price);

        // Remove voucher
        $responseRemove = $this->actingAs($user)->post(route('booking.remove_voucher', $booking->id));
        $responseRemove->assertRedirect();
        $booking->refresh();
        $this->assertNull($booking->promotion_id);
        $this->assertEquals(0, $booking->discount_amount);
        $this->assertEquals(100000, $booking->total_price);
    }
}

