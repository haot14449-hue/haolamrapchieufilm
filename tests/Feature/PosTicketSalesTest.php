<?php

namespace Tests\Feature;

use App\Mail\TicketConfirmationMail;
use App\Models\Cinema;
use App\Models\Food;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PosTicketSalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_customer_cannot_access_pos()
    {
        $response = $this->get(route('pos.index'));
        $response->assertRedirect('/login');

        $customer = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($customer)->get(route('pos.index'));
        $response->assertRedirect('/');
    }

    public function test_staff_and_admin_can_access_pos()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $response = $this->actingAs($staff)->get(route('pos.index'));
        $response->assertStatus(200);
        $response->assertSee('QUẦY VÉ POS');

        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get(route('pos.index'));
        $response->assertStatus(200);
    }

    public function test_staff_cannot_access_admin_panel()
    {
        $staff = User::factory()->create(['role' => 'staff']);

        // Accessing /admin or /admin/dashboard should redirect staff to /pos
        $response = $this->actingAs($staff)->get(route('admin.dashboard'));
        $response->assertRedirect(route('pos.index'));

        // Accessing other admin routes should also redirect staff to /pos
        $responseMovies = $this->actingAs($staff)->get(route('admin.movies.index'));
        $responseMovies->assertRedirect(route('pos.index'));
    }

    public function test_customer_lookup_by_phone()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $existingCustomer = User::factory()->create([
            'name' => 'Trần Văn Hào',
            'phone' => '0988776655',
            'birthday' => '2000-05-15',
            'email' => 'haotran@gmail.com',
            'role' => 'customer',
            'points' => 50,
        ]);

        // 1. Existing customer lookup
        $response = $this->actingAs($staff)->getJson(route('pos.lookup_customer', ['phone' => '0988776655']));
        $response->assertStatus(200);
        $response->assertJson([
            'found' => true,
            'customer' => [
                'name' => 'Trần Văn Hào',
                'phone' => '0988776655',
                'birthday' => '2000-05-15',
                'email' => 'haotran@gmail.com',
                'points' => 50,
            ],
        ]);

        // 2. Non-existent customer lookup
        $response = $this->actingAs($staff)->getJson(route('pos.lookup_customer', ['phone' => '0911111111']));
        $response->assertStatus(200);
        $response->assertJson(['found' => false]);
    }

    public function test_pos_checkout_with_cash_and_customer_creation_and_email_sent()
    {
        Mail::fake();

        $staff = User::factory()->create(['role' => 'staff', 'name' => 'Thu Ngân 01']);
        $cinema = Cinema::create(['name' => 'HCTV Landmark', 'location' => 'Q1', 'city' => 'HCM']);
        $room = Room::create(['name' => 'Phòng 01', 'cinema_id' => $cinema->id, 'total_seats' => 20]);
        $movie = Movie::create(['title' => 'Dune Part Two', 'duration' => 160, 'genre' => 'Sci-Fi']);
        $seat1 = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 1, 'type' => 'standard']);
        $seat2 = Seat::create(['room_id' => $room->id, 'row' => 'A', 'number' => 2, 'type' => 'vip']);
        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(2),
            'end_time' => now()->addHours(4),
            'price' => 100000,
        ]);

        $food = Food::create(['name' => 'Bắp Ngọt Lớn', 'price' => 60000]);

        $payload = [
            'showtime_id' => $showtime->id,
            'seats' => [$seat1->id, $seat2->id],
            'customer_name' => 'Nguyễn Thị Hoa',
            'customer_phone' => '0933221100',
            'customer_birthday' => '1998-10-20',
            'customer_email' => 'hoanguyen@example.com',
            'payment_method' => 'cash',
            'cash_given' => 300000,
            'foods' => [
                ['food_id' => $food->id, 'quantity' => 1],
            ],
        ];

        $response = $this->actingAs($staff)->postJson(route('pos.checkout'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'booking' => [
                'customer_name' => 'Nguyễn Thị Hoa',
                'customer_phone' => '0933221100',
                'customer_email' => 'hoanguyen@example.com',
                'payment_method' => 'Tiền mặt',
            ],
        ]);

        // Check new customer created in users table with birthday
        $this->assertDatabaseHas('users', [
            'name' => 'Nguyễn Thị Hoa',
            'phone' => '0933221100',
            'email' => 'hoanguyen@example.com',
            'role' => 'customer',
        ]);

        // Check booking created and marked as paid
        $this->assertDatabaseHas('bookings', [
            'showtime_id' => $showtime->id,
            'status' => 'paid',
            'payment_method' => 'Tiền mặt',
            'cashier_id' => $staff->id,
        ]);

        // Check tickets created
        $this->assertDatabaseHas('tickets', [
            'seat_id' => $seat1->id,
            'status' => 'booked',
        ]);
        $this->assertDatabaseHas('tickets', [
            'seat_id' => $seat2->id,
            'status' => 'booked',
        ]);

        // Check confirmation email sent
        Mail::assertSent(TicketConfirmationMail::class, function ($mail) {
            return $mail->hasTo('hoanguyen@example.com');
        });
    }

    public function test_customer_returning_second_time_auto_lookup_and_booking()
    {
        Mail::fake();

        $staff = User::factory()->create(['role' => 'staff']);
        $existingCustomer = User::factory()->create([
            'name' => 'Nguyễn Thị Hoa',
            'phone' => '0933221100',
            'birthday' => '1998-10-20',
            'email' => 'hoanguyen@example.com',
            'role' => 'customer',
            'points' => 200,
        ]);

        $cinema = Cinema::create(['name' => 'HCTV Landmark', 'location' => 'Q1', 'city' => 'HCM']);
        $room = Room::create(['name' => 'Phòng 02', 'cinema_id' => $cinema->id, 'total_seats' => 20]);
        $movie = Movie::create(['title' => 'Avengers Endgame', 'duration' => 180, 'genre' => 'Action']);
        $seat = Seat::create(['room_id' => $room->id, 'row' => 'B', 'number' => 5, 'type' => 'standard']);
        $showtime = Showtime::create([
            'movie_id' => $movie->id,
            'room_id' => $room->id,
            'start_time' => now()->addHours(3),
            'price' => 100000,
        ]);

        // 1. Staff looks up customer by phone
        $lookupRes = $this->actingAs($staff)->getJson(route('pos.lookup_customer', ['phone' => '0933221100']));
        $lookupRes->assertStatus(200);
        $lookupRes->assertJson([
            'found' => true,
            'customer' => [
                'name' => 'Nguyễn Thị Hoa',
                'email' => 'hoanguyen@example.com',
            ],
        ]);

        // 2. Staff checks out order with MB Bank QR
        $payload = [
            'showtime_id' => $showtime->id,
            'seats' => [$seat->id],
            'customer_name' => 'Nguyễn Thị Hoa',
            'customer_phone' => '0933221100',
            'customer_birthday' => '1998-10-20',
            'customer_email' => 'hoanguyen@example.com',
            'payment_method' => 'sepay_mb',
        ];

        $checkoutRes = $this->actingAs($staff)->postJson(route('pos.checkout'), $payload);
        $checkoutRes->assertStatus(200);

        // Assert customer earned more points
        $existingCustomer->refresh();
        $this->assertGreaterThan(200, $existingCustomer->points);

        // Assert booking associated with this existing customer
        $this->assertDatabaseHas('bookings', [
            'user_id' => $existingCustomer->id,
            'showtime_id' => $showtime->id,
            'payment_method' => 'MB Bank QR (SePay)',
            'status' => 'paid',
        ]);
    }
}
