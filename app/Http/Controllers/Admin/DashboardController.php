<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Movie;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        Booking::cleanupExpired();

        $totalUsers = User::where('role', 'customer')->count();
        $totalRevenue = (float) Booking::where('status', 'paid')->sum('total_price');
        $totalBookings = Booking::where('status', 'paid')->count();
        $totalMovies = Movie::count();

        // Total tickets sold across all paid bookings
        $totalTicketsSold = (int) DB::table('tickets')
            ->join('bookings', 'tickets.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->where('tickets.status', '!=', 'cancelled')
            ->count();

        // 1. Chart Data: Daily Revenue for 7, 14, 30 days
        $chartData = [
            '7_days' => $this->getDailyRevenueData(7),
            '14_days' => $this->getDailyRevenueData(14),
            '30_days' => $this->getDailyRevenueData(30),
        ];

        // 2. Movie Rankings: Top movies by tickets sold
        $ticketSub = DB::table('tickets')
            ->join('bookings', 'tickets.booking_id', '=', 'bookings.id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('bookings.status', 'paid')
            ->where('tickets.status', '!=', 'cancelled')
            ->select('showtimes.movie_id', DB::raw('COUNT(tickets.id) as tickets_sold'))
            ->groupBy('showtimes.movie_id');

        $revenueSub = DB::table('bookings')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('bookings.status', 'paid')
            ->select('showtimes.movie_id', DB::raw('SUM(bookings.total_price) as total_revenue'))
            ->groupBy('showtimes.movie_id');

        $movieRankings = Movie::query()
            ->leftJoinSub($ticketSub, 't_stats', 'movies.id', '=', 't_stats.movie_id')
            ->leftJoinSub($revenueSub, 'r_stats', 'movies.id', '=', 'r_stats.movie_id')
            ->select(
                'movies.id',
                'movies.title',
                'movies.poster_url',
                'movies.duration',
                'movies.genre',
                DB::raw('COALESCE(t_stats.tickets_sold, 0) as tickets_sold'),
                DB::raw('COALESCE(r_stats.total_revenue, 0) as total_revenue')
            )
            ->orderByDesc('tickets_sold')
            ->orderByDesc('total_revenue')
            ->take(8)
            ->get();

        $recentBookings = Booking::with('user', 'showtime.movie')->orderBy('created_at', 'desc')->take(6)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRevenue',
            'totalBookings',
            'totalMovies',
            'totalTicketsSold',
            'chartData',
            'movieRankings',
            'recentBookings'
        ));
    }

    private function getDailyRevenueData(int $days): array
    {
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $records = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as order_date'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(id) as orders_count')
            )
            ->groupBy('order_date')
            ->get()
            ->keyBy('order_date');

        $period = CarbonPeriod::create($startDate, '1 day', $endDate);

        $labels = [];
        $fullLabels = [];
        $revenues = [];
        $orders = [];

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            $fullLabels[] = $date->format('d/m/Y');
            $revenues[] = isset($records[$dateStr]) ? (float) $records[$dateStr]->total_revenue : 0;
            $orders[] = isset($records[$dateStr]) ? (int) $records[$dateStr]->orders_count : 0;
        }

        return [
            'labels' => $labels,
            'fullLabels' => $fullLabels,
            'revenues' => $revenues,
            'orders' => $orders,
            'total' => (float) array_sum($revenues),
            'totalOrders' => (int) array_sum($orders),
        ];
    }
}
