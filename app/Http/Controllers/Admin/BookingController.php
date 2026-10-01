<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        Booking::cleanupExpired();

        $query = Booking::with(['user', 'showtime.movie', 'showtime.room.cinema'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->get();

        $statusCounts = [
            'all' => Booking::count(),
            'paid' => Booking::where('status', 'paid')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'statusCounts'));
    }

    public function show($id)
    {
        Booking::cleanupExpired();

        $booking = Booking::with(['user', 'showtime.movie', 'showtime.room.cinema', 'tickets.seat', 'foods'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        if ($request->status === 'cancelled') {
            $booking->releaseSeats();
        } else {
            $booking->status = $request->status;
            if ($request->status === 'paid') {
                $booking->tickets()->update(['status' => 'booked']);
            }
            $booking->save();
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}
