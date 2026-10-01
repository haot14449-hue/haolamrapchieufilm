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
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowtimeAndSeatSynchronizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_showtimes_on_pos_and_online_are_synchronized_for_date_and_cinema()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $customer = User::factory()->create(['role' => 'customer']);

        $cinemaHanoi = Cinema::create(['name' => 'HCTV Cinematic (Hà Nội)', 'location' => 'Hà Nội', 'city' => 'Hà Nội']);
        $cinemaHCM = Cinema::create(['name' => 'HCTV Cinematic (TP.HCM)', 'location' => 'TP.HCM', 'city' => 'TP.HCM']);

        $roomHanoi = Room::create(['cinema_id' => $cinemaHanoi->id, 'name' => 'Ai Mắc', 'total_seats' => 80]);
        $roomHCM = Room::create(['cinema_id' => $cinemaHCM->id, 'name' => 'ScreenX', 'total_seats' => 100]);

        $movie = Movie::create([
            'title' => 'Oppenheimer',
            'duration' => 180,
            'genre' => 'Tâm lý, Giật gân',
            'release_date' => now(),
        ]);

        $todayStr = Carbon::today()->format('Y-m-d');

        // Showtime at Hanoi today
        $showtimeHanoi = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $roomHanoi->id,
            'start_time' => Carbon::today()->setHour(10)->setMinute(15),
            'price' => 100000,
            'format' => '3D',
        ]);

        // Showtime at HCM today
        $showtimeHCM = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $roomHCM->id,
            'start_time' => Carbon::today()->setHour(14)->setMinute(30),
            'price' => 90000,
            'format' => '2D',
        ]);

        // 1. Check POS showtimes API for Hanoi
        $posResponseHanoi = $this->actingAs($staff)->getJson(route('pos.showtimes', [
            'cinema_id' => $cinemaHanoi->id,
            'date' => $todayStr,
        ]));
        $posResponseHanoi->assertOk();
        $posMoviesHanoi = $posResponseHanoi->json('movies');
        $this->assertCount(1, $posMoviesHanoi);
        $this->assertEquals('Oppenheimer', $posMoviesHanoi[0]['movie']['title']);
        $this->assertEquals(1, count($posMoviesHanoi[0]['showtimes']));
        $this->assertEquals('10:15', $posMoviesHanoi[0]['showtimes'][0]['start_time']);

        // 2. Check Online showtimes page for Hanoi
        $onlineResponseHanoi = $this->actingAs($customer)->get(route('showtimes', [
            'cinema_id' => $cinemaHanoi->id,
            'date' => $todayStr,
        ]));
        $onlineResponseHanoi->assertOk();
        $onlineResponseHanoi->assertSee('Oppenheimer');
        $onlineResponseHanoi->assertSee('10:15');
        $onlineResponseHanoi->assertSee(route('booking.seats', $showtimeHanoi->id));
        $onlineResponseHanoi->assertDontSee(route('booking.seats', $showtimeHCM->id));

        // 3. Check Online showtimes page with "Tất Cả Rạp"
        $onlineResponseAll = $this->actingAs($customer)->get(route('showtimes', [
            'date' => $todayStr,
        ]));
        $onlineResponseAll->assertOk();
        $onlineResponseAll->assertSee(route('booking.seats', $showtimeHanoi->id));
        $onlineResponseAll->assertSee(route('booking.seats', $showtimeHCM->id));
    }

    public function test_seat_held_at_pos_cannot_be_selected_or_processed_online()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $onlineUser = User::factory()->create(['role' => 'customer']);

        $cinema = Cinema::create(['name' => 'HCTV Cinematic (Hà Nội)', 'location' => 'Hà Nội']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Ai Mắc', 'total_seats' => 80]);
        $movie = Movie::create(['title' => 'Oppenheimer', 'duration' => 180, 'genre' => 'Drama']);
        $seatD5 = Seat::create(['room_id' => $room->id, 'row' => 'D', 'number' => 5, 'type' => 'vip']);
        $seatD6 = Seat::create(['room_id' => $room->id, 'row' => 'D', 'number' => 6, 'type' => 'standard']);

        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(2),
            'price' => 100000,
        ]);

        // 1. Staff holds seat D5 at POS counter
        $holdResponse = $this->actingAs($staff)->postJson(route('pos.hold_seats'), [
            'showtime_id' => $showtime->id,
            'seats' => [$seatD5->id],
        ]);
        $holdResponse->assertOk();
        $holdResponse->assertJson(['success' => true]);
        $holdBookingId = $holdResponse->json('hold_booking_id');
        $this->assertNotNull($holdBookingId);

        // 2. Real-time seat status for online booking must mark D5 as booked/held
        $statusResponse = $this->actingAs($onlineUser)->getJson(route('booking.seat_status', $showtime->id));
        $statusResponse->assertOk();
        $this->assertContains($seatD5->id, $statusResponse->json('booked_seats'));
        $this->assertNotContains($seatD6->id, $statusResponse->json('booked_seats'));

        // 3. Online user attempts to submit processSeats for D5 -> must be blocked with error
        $onlineBookResponse = $this->actingAs($onlineUser)->post(route('booking.process_seats', $showtime->id), [
            'seats' => [$seatD5->id],
        ]);
        $onlineBookResponse->assertSessionHas('error');
        $this->assertStringContainsString('D5', session('error'));

        // Ensure online user could not create a booking
        $this->assertDatabaseMissing('bookings', [
            'showtime_id' => $showtime->id,
            'user_id' => $onlineUser->id,
        ]);

        // 4. Staff at POS checks out D5 with customer -> converts to paid
        $checkoutResponse = $this->actingAs($staff)->postJson(route('pos.checkout'), [
            'showtime_id' => $showtime->id,
            'seats' => [$seatD5->id],
            'hold_booking_id' => $holdBookingId,
            'customer_name' => 'Nguyễn Văn Test',
            'customer_phone' => '0988112233',
            'customer_email' => 'test@gmail.com',
            'payment_method' => 'cash',
        ]);
        $checkoutResponse->assertOk();
        $checkoutResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('bookings', [
            'id' => $holdBookingId,
            'status' => 'paid',
            'showtime_id' => $showtime->id,
        ]);
    }

    public function test_seat_held_or_booked_online_cannot_be_selected_or_checked_out_at_pos()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $onlineUser = User::factory()->create(['role' => 'customer']);

        $cinema = Cinema::create(['name' => 'HCTV Cinematic (Hà Nội)', 'location' => 'Hà Nội']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Ai Mắc', 'total_seats' => 80]);
        $movie = Movie::create(['title' => 'Oppenheimer', 'duration' => 180, 'genre' => 'Drama']);
        $seatA1 = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 1, 'type' => 'standard']);

        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(2),
            'price' => 100000,
        ]);

        // 1. Online user reserves seat A1 (status = pending)
        $onlineResponse = $this->actingAs($onlineUser)->post(route('booking.process_seats', $showtime->id), [
            'seats' => [$seatA1->id],
        ]);
        $onlineResponse->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'showtime_id' => $showtime->id,
            'user_id' => $onlineUser->id,
            'status' => 'pending',
        ]);

        // 2. POS seat map API must show A1 as is_booked = true
        $posSeatsResponse = $this->actingAs($staff)->getJson(route('pos.showtime_seats', $showtime->id));
        $posSeatsResponse->assertOk();
        $seatsData = collect($posSeatsResponse->json('seats'));
        $seatA1Data = $seatsData->firstWhere('id', $seatA1->id);
        $this->assertTrue($seatA1Data['is_booked']);

        // 3. POS attempts to hold seat A1 -> blocked with conflict
        $posHoldResponse = $this->actingAs($staff)->postJson(route('pos.hold_seats'), [
            'showtime_id' => $showtime->id,
            'seats' => [$seatA1->id],
        ]);
        $posHoldResponse->assertStatus(422);
        $this->assertFalse($posHoldResponse->json('success'));

        // 4. POS attempts checkout for seat A1 -> blocked
        $posCheckoutResponse = $this->actingAs($staff)->postJson(route('pos.checkout'), [
            'showtime_id' => $showtime->id,
            'seats' => [$seatA1->id],
            'customer_name' => 'Khách Vãng Lai',
            'customer_phone' => '0912345678',
            'customer_email' => 'guest@gmail.com',
            'payment_method' => 'cash',
        ]);
        $posCheckoutResponse->assertStatus(422);
        $this->assertFalse($posCheckoutResponse->json('success'));
    }

    public function test_releasing_pos_hold_immediately_frees_seat_for_online_booking()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $onlineUser = User::factory()->create(['role' => 'customer']);

        $cinema = Cinema::create(['name' => 'HCTV Cinematic', 'location' => 'Hà Nội']);
        $room = Room::create(['cinema_id' => $cinema->id, 'name' => 'Ai Mắc']);
        $movie = Movie::create(['title' => 'Avengers Endgame', 'duration' => 180]);
        $seat = Seat::create(['room_id' => $room->id, 'row' => 'C', 'number' => 8]);

        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(2),
            'price' => 100000,
        ]);

        // 1. Hold seat at POS
        $holdRes = $this->actingAs($staff)->postJson(route('pos.hold_seats'), [
            'showtime_id' => $showtime->id,
            'seats' => [$seat->id],
        ]);
        $holdBookingId = $holdRes->json('hold_booking_id');

        // Verify online status says booked
        $status1 = $this->actingAs($onlineUser)->getJson(route('booking.seat_status', $showtime->id));
        $this->assertContains($seat->id, $status1->json('booked_seats'));

        // 2. Release hold at POS (cashier resets or unchecks)
        $releaseRes = $this->actingAs($staff)->postJson(route('pos.release_hold'), [
            'hold_booking_id' => $holdBookingId,
        ]);
        $releaseRes->assertOk();

        // 3. Verify online status now says free
        $status2 = $this->actingAs($onlineUser)->getJson(route('booking.seat_status', $showtime->id));
        $this->assertNotContains($seat->id, $status2->json('booked_seats'));

        // 4. Online user can now successfully book the seat
        $bookRes = $this->actingAs($onlineUser)->post(route('booking.process_seats', $showtime->id), [
            'seats' => [$seat->id],
        ]);
        $bookRes->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'showtime_id' => $showtime->id,
            'user_id' => $onlineUser->id,
            'status' => 'pending',
        ]);
    }

    public function test_pos_and_online_seat_maps_use_identical_layout_data_and_pricing_surcharges()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $onlineUser = User::factory()->create(['role' => 'customer']);

        $cinema = Cinema::create(['name' => 'HCTV Quốc Thanh', 'location' => 'TP.HCM']);

        // Custom layout with standard, vip, deluxe, sweetbox, aisles and empty spaces
        $customGrid = [
            [
                ['type' => 'standard', 'row' => 'A', 'number' => 1],
                ['type' => 'standard', 'row' => 'A', 'number' => 2],
                ['type' => 'aisle'],
                ['type' => 'vip', 'row' => 'A', 'number' => 3],
            ],
            [
                ['type' => 'deluxe', 'row' => 'B', 'number' => 1],
                ['type' => 'empty'],
                ['type' => 'aisle'],
                ['type' => 'sweetbox', 'row' => 'B', 'number' => 2],
            ]
        ];

        $room = Room::create([
            'cinema_id' => $cinema->id,
            'name' => 'Phòng 01 (IMAX)',
            'total_seats' => 5,
            'layout_data' => json_encode(['grid' => $customGrid]),
        ]);

        $seatA1 = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 1, 'type' => 'standard']);
        $seatA2 = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 2, 'type' => 'standard']);
        $seatA3 = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 3, 'type' => 'vip']);
        $seatB1 = Seat::create(['room_id' => $room->id, 'row' => 'B', 'number' => 1, 'type' => 'deluxe']);
        $seatB2 = Seat::create(['room_id' => $room->id, 'row' => 'B', 'number' => 2, 'type' => 'sweetbox']);

        $movie = Movie::create(['title' => 'Dune 2', 'duration' => 165]);

        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(3),
            'price' => 100000,
        ]);

        // 1. Fetch POS seats
        $posRes = $this->actingAs($staff)->getJson(route('pos.showtime_seats', $showtime->id));
        $posRes->assertOk();
        $this->assertTrue($posRes->json('has_layout'));
        $this->assertEquals(4, $posRes->json('num_cols'));
        $gridRows = $posRes->json('grid_rows');
        $this->assertCount(2, $gridRows);

        // Row A checks
        $this->assertEquals('A', $gridRows[0]['row_letter']);
        $this->assertFalse($gridRows[0]['cells'][0]['is_empty']);
        $this->assertEquals('standard', $gridRows[0]['cells'][0]['type']);
        $this->assertEquals(100000, $gridRows[0]['cells'][0]['price']);

        $this->assertTrue($gridRows[0]['cells'][2]['is_empty']); // Aisle

        $this->assertFalse($gridRows[0]['cells'][3]['is_empty']);
        $this->assertEquals('vip', $gridRows[0]['cells'][3]['type']);
        $this->assertEquals(120000, $gridRows[0]['cells'][3]['price']); // 100k + 20k

        // Row B checks
        $this->assertEquals('B', $gridRows[1]['row_letter']);
        $this->assertFalse($gridRows[1]['cells'][0]['is_empty']);
        $this->assertEquals('deluxe', $gridRows[1]['cells'][0]['type']);
        $this->assertEquals(130000, $gridRows[1]['cells'][0]['price']); // 100k + 30k

        $this->assertTrue($gridRows[1]['cells'][1]['is_empty']); // Empty gap
        $this->assertTrue($gridRows[1]['cells'][2]['is_empty']); // Aisle

        $this->assertFalse($gridRows[1]['cells'][3]['is_empty']);
        $this->assertEquals('sweetbox', $gridRows[1]['cells'][3]['type']);
        $this->assertEquals(140000, $gridRows[1]['cells'][3]['price']); // 100k + 40k

        // 2. Online view checks
        $onlineRes = $this->actingAs($onlineUser)->get(route('booking.seats', $showtime->id));
        $onlineRes->assertOk();
        $onlineRes->assertSee('MÀN HÌNH CHIẾU');
        $onlineRes->assertSee('A1');
        $onlineRes->assertSee('B2');

        // 3. POS checkout with Deluxe and Sweetbox seats applies the correct prices
        $checkoutRes = $this->actingAs($staff)->postJson(route('pos.checkout'), [
            'showtime_id' => $showtime->id,
            'seats' => [$seatB1->id, $seatB2->id], // 130k + 140k = 270k
            'customer_name' => 'Nguyễn Văn Deluxe',
            'customer_phone' => '0933333333',
            'customer_email' => 'deluxe@gmail.com',
            'payment_method' => 'cash',
        ]);

        $checkoutRes->assertOk();
        $this->assertEquals(270000, $checkoutRes->json('booking.total_price'));
        $this->assertDatabaseHas('bookings', [
            'showtime_id' => $showtime->id,
            'total_price' => 270000,
            'status' => 'paid',
        ]);
    }
}

