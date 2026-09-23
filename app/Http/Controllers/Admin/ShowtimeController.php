<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Cinema;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Showtime::with(['movie', 'room.cinema'])->orderBy('start_time', 'desc');

        if ($request->filled('cinema_id')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('cinema_id', $request->cinema_id);
            });
        }
        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        $showtimes = $query->get();
        $cinemas = Cinema::with('rooms')->get();
        $movies = Movie::orderBy('title')->get();

        return view('admin.showtimes.index', compact('showtimes', 'cinemas', 'movies'));
    }

    public function create()
    {
        $movies = Movie::all();
        $cinemas = Cinema::with('rooms')->get();
        $rooms = Room::with('cinema')->get();
        return view('admin.showtimes.form', compact('movies', 'cinemas', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);

        $showtime = new Showtime();
        $showtime->movie_id = $request->movie_id;
        $showtime->room_id = $request->room_id;
        $showtime->start_time = $request->start_time;
        $showtime->price = $request->price;
        $showtime->save();

        return redirect()->route('admin.showtimes.index')->with('success', 'Thêm suất chiếu thành công!');
    }

    public function edit(Showtime $showtime)
    {
        $showtime->load('room.cinema');
        $movies = Movie::all();
        $cinemas = Cinema::with('rooms')->get();
        $rooms = Room::with('cinema')->get();
        return view('admin.showtimes.form', compact('showtime', 'movies', 'cinemas', 'rooms'));
    }

    public function update(Request $request, Showtime $showtime)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);

        $showtime->movie_id = $request->movie_id;
        $showtime->room_id = $request->room_id;
        $showtime->start_time = $request->start_time;
        $showtime->price = $request->price;
        $showtime->save();

        return redirect()->route('admin.showtimes.index')->with('success', 'Cập nhật suất chiếu thành công!');
    }

    public function destroy(Showtime $showtime)
    {
        // Simple deletion, no check for bookings in this mockup
        $showtime->delete();
        return redirect()->route('admin.showtimes.index')->with('success', 'Xóa suất chiếu thành công!');
    }

    /**
     * API: Thuật toán AI tạo suất chiếu tự động, tối ưu giờ vàng, tránh trùng lịch & trùng phòng.
     */
    public function generateAutoSchedule(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'golden_hour' => 'nullable|string',
            'cleaning_gap' => 'nullable|integer|min:5|max:60',
            'movies' => 'required|array|min:1',
            'movies.*.id' => 'required|exists:movies,id',
        ]);

        $cinema = Cinema::with('rooms')->find($request->cinema_id);
        if (!$cinema || $cinema->rooms->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Rạp chiếu này hiện chưa có phòng chiếu nào để xếp lịch!'
            ], 422);
        }

        $rooms = $cinema->rooms;
        $roomIds = $rooms->pluck('id')->toArray();

        $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
        
        $opStartHour = $request->input('start_time', '08:00');
        $opEndHour = $request->input('end_time', '22:30');
        $goldenHourTime = $request->input('golden_hour', '19:00');
        $cleaningGap = (int)$request->input('cleaning_gap', 15);

        // Fetch selected movie models
        $configuredMovies = collect($request->input('movies'));
        $movieIds = $configuredMovies->pluck('id')->toArray();
        $moviesDb = Movie::whereIn('id', $movieIds)->get()->keyBy('id');

        $movieList = [];
        foreach ($configuredMovies as $cm) {
            $mId = $cm['id'];
            if (isset($moviesDb[$mId])) {
                $m = $moviesDb[$mId];
                $movieList[] = [
                    'id' => $m->id,
                    'title' => $m->title,
                    'duration' => $m->duration ?: 110,
                    'poster_url' => $m->poster_url,
                    'genre' => $m->genre,
                    'format' => $cm['format'] ?? '2D',
                    'language' => $cm['language'] ?? 'Phụ Đề',
                    'price' => isset($cm['price']) && $cm['price'] > 0 ? (float)$cm['price'] : 100000,
                ];
            }
        }

        if (empty($movieList)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng chọn ít nhất 1 bộ phim hợp lệ!'
            ], 422);
        }

        // Fetch existing showtimes in database for this cinema's rooms across the date range to avoid conflict
        $existingShowtimes = Showtime::whereIn('room_id', $roomIds)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with('movie')
            ->get();

        // Build busy intervals: $busySlots[room_id][date_str][] = [start, end_with_gap]
        $busySlots = [];
        foreach ($existingShowtimes as $ex) {
            $exStart = \Carbon\Carbon::parse($ex->start_time);
            $dateStr = $exStart->format('Y-m-d');
            $duration = ($ex->movie && $ex->movie->duration) ? $ex->movie->duration : 110;
            $exEnd = $exStart->copy()->addMinutes($duration + $cleaningGap);
            $busySlots[$ex->room_id][$dateStr][] = [
                'start' => $exStart,
                'end' => $exEnd,
            ];
        }

        // Build array of dates
        $dates = [];
        $curDate = $startDate->copy();
        while ($curDate->lte($endDate)) {
            $dates[] = $curDate->format('Y-m-d');
            $curDate->addDay();
        }

        $generatedShowtimes = [];
        $goldenCount = 0;

        // Schedule generation loop
        foreach ($dates as $dateStr) {
            $dayCarbon = \Carbon\Carbon::parse($dateStr);
            $isWeekend = $dayCarbon->isWeekend();

            $dayStart = \Carbon\Carbon::parse("{$dateStr} {$opStartHour}");
            $dayEnd = \Carbon\Carbon::parse("{$dateStr} {$opEndHour}");
            $goldenTime = \Carbon\Carbon::parse("{$dateStr} {$goldenHourTime}");
            $goldenStart = $goldenTime->copy()->subMinutes(60); // 18:00
            $goldenEnd = $goldenTime->copy()->addMinutes(120);   // 21:00

            // Rotate movies per room to create diverse schedules
            $movieIndex = 0;

            foreach ($rooms as $rIdx => $room) {
                $pointer = $dayStart->copy();
                // Stagger room start times slightly (e.g. 10-15 mins) so ticket checks don't bottleneck
                if ($rIdx > 0) {
                    $pointer->addMinutes(($rIdx * 10) % 30);
                }

                $safetyLimit = 0; // Prevent infinite loop
                while ($pointer->lt($dayEnd) && $safetyLimit < 50) {
                    $safetyLimit++;

                    // Check if current time overlaps with an existing DB showtime in this room
                    $hasOverlap = false;
                    if (isset($busySlots[$room->id][$dateStr])) {
                        foreach ($busySlots[$room->id][$dateStr] as $bSlot) {
                            if ($pointer->lt($bSlot['end']) && $pointer->copy()->addMinutes(30)->gt($bSlot['start'])) {
                                // Jump pointer to end of this busy slot
                                $pointer = $bSlot['end']->copy();
                                // Round to nearest 5 minutes
                                $min = (int)$pointer->format('i');
                                $roundedMin = ceil($min / 5) * 5;
                                $pointer->setMinute($roundedMin % 60);
                                if ($roundedMin >= 60) $pointer->addHour();
                                $hasOverlap = true;
                                break;
                            }
                        }
                    }
                    if ($hasOverlap) {
                        continue;
                    }

                    // Candidate movie selection
                    // If in golden hour, pick longest/most popular movie
                    $isNearGolden = ($pointer->gte($goldenStart) && $pointer->lte($goldenEnd));
                    
                    if ($isNearGolden) {
                        // Pick movie with high duration or format matching room
                        $candidateMovie = collect($movieList)->sortByDesc('duration')->first();
                    } else {
                        // Round-robin selection
                        $candidateMovie = $movieList[$movieIndex % count($movieList)];
                        $movieIndex++;
                    }

                    $duration = $candidateMovie['duration'];
                    $candStart = $pointer->copy();
                    $candEnd = $candStart->copy()->addMinutes($duration);
                    $slotEndWithClean = $candEnd->copy()->addMinutes($cleaningGap);

                    // Check if the proposed end time extends too far past closing hour
                    if ($candStart->gte($dayEnd)) {
                        break;
                    }

                    // Check again if [candStart, slotEndWithClean] collides with any busy slot
                    $collision = false;
                    if (isset($busySlots[$room->id][$dateStr])) {
                        foreach ($busySlots[$room->id][$dateStr] as $bSlot) {
                            if ($candStart->lt($bSlot['end']) && $slotEndWithClean->gt($bSlot['start'])) {
                                $pointer = $bSlot['end']->copy();
                                $collision = true;
                                break;
                            }
                        }
                    }
                    if ($collision) {
                        continue;
                    }

                    // Conflict-free slot found! Add to generated schedule
                    $isGolden = ($candStart->gte($goldenStart) && $candStart->lte($goldenEnd));
                    if ($isGolden) $goldenCount++;

                    $generatedShowtimes[] = [
                        'temp_id' => 'st_' . uniqid(),
                        'movie_id' => $candidateMovie['id'],
                        'movie_title' => $candidateMovie['title'],
                        'movie_poster' => $candidateMovie['poster_url'],
                        'movie_duration' => $duration,
                        'room_id' => $room->id,
                        'room_name' => $room->name,
                        'cinema_id' => $cinema->id,
                        'cinema_name' => $cinema->name,
                        'date' => $dateStr,
                        'day_label' => $dayCarbon->locale('vi')->isoFormat('ddd DD/MM'),
                        'start_time' => $candStart->format('Y-m-d H:i:s'),
                        'start_time_formatted' => $candStart->format('H:i'),
                        'end_time' => $candEnd->format('Y-m-d H:i:s'),
                        'end_time_formatted' => $candEnd->format('H:i'),
                        'format' => $candidateMovie['format'],
                        'language' => $candidateMovie['language'],
                        'price' => $candidateMovie['price'],
                        'is_golden_hour' => $isGolden,
                    ];

                    // Register busy slot in memory
                    $busySlots[$room->id][$dateStr][] = [
                        'start' => $candStart,
                        'end' => $slotEndWithClean,
                    ];

                    // Advance pointer for next showtime
                    $pointer = $slotEndWithClean->copy();
                    // Round up to next 5-min block
                    $m = (int)$pointer->format('i');
                    $rem = $m % 5;
                    if ($rem !== 0) {
                        $pointer->addMinutes(5 - $rem);
                    }
                }
            }
        }

        // Sort generated showtimes by start_time
        usort($generatedShowtimes, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });

        return response()->json([
            'success' => true,
            'message' => "Đã tạo thành công " . count($generatedShowtimes) . " suất chiếu thông minh!",
            'total_showtimes' => count($generatedShowtimes),
            'golden_hour_count' => $goldenCount,
            'dates' => $dates,
            'rooms' => $rooms->map(fn($r) => ['id' => $r->id, 'name' => $r->name]),
            'showtimes' => $generatedShowtimes,
        ]);
    }

    /**
     * API: Lưu hàng loạt các suất chiếu tự động vào cơ sở dữ liệu.
     */
    public function saveAutoSchedule(Request $request)
    {
        $request->validate([
            'showtimes' => 'required|array|min:1',
            'showtimes.*.movie_id' => 'required|exists:movies,id',
            'showtimes.*.room_id' => 'required|exists:rooms,id',
            'showtimes.*.start_time' => 'required|date',
            'showtimes.*.price' => 'required|numeric|min:0',
            'showtimes.*.format' => 'nullable|string',
            'showtimes.*.language' => 'nullable|string',
        ]);

        $items = $request->input('showtimes');

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $savedCount = 0;
            foreach ($items as $item) {
                // Ensure no direct duplicate at the exact same start_time in the exact same room
                $exists = Showtime::where('room_id', $item['room_id'])
                    ->where('start_time', $item['start_time'])
                    ->exists();

                if (!$exists) {
                    Showtime::create([
                        'movie_id' => $item['movie_id'],
                        'room_id' => $item['room_id'],
                        'start_time' => $item['start_time'],
                        'price' => $item['price'] ?? 100000,
                        'format' => $item['format'] ?? '2D',
                        'language' => $item['language'] ?? 'Phụ Đề',
                    ]);
                    $savedCount++;
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Đã lưu thành công {$savedCount} suất chiếu vào cơ sở dữ liệu!",
                'count' => $savedCount,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lưu lịch chiếu: ' . $e->getMessage()
            ], 500);
        }
    }
}
