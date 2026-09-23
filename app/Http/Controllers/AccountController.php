<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class AccountController extends Controller
{
    public function tickets()
    {
        // Get all bookings for the authenticated user, order by newest
        $bookings = Booking::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('account.tickets', compact('bookings'));
    }
}
