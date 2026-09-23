<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Movie;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'customer')->count();
        $totalRevenue = Booking::where('status', 'paid')->sum('total_price');
        $totalBookings = Booking::where('status', 'paid')->count();
        $totalMovies = Movie::count();
        
        $recentBookings = Booking::with('user', 'showtime.movie')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalRevenue', 'totalBookings', 'totalMovies', 'recentBookings'));
    }
}
