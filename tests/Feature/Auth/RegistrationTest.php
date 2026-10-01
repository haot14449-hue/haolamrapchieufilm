<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('register.otp.notice'));
        $this->assertTrue(session()->has('register_pending'));

        $pending = session('register_pending');
        $otp = $pending['otp'];

        $verifyResponse = $this->post(route('register.otp.verify'), [
            'otp' => $otp,
        ]);

        $this->assertAuthenticated();
        $verifyResponse->assertRedirect(route('home'));
    }
}
