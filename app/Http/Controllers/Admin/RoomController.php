<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Store a newly created room for a cinema.
     */
    public function store(Request $request, $cinema_id)
    {
        $cinema = Cinema::findOrFail($cinema_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'total_rows' => 'nullable|integer|min:3|max:26',
            'total_columns' => 'nullable|integer|min:4|max:30',
        ]);

        $totalRows = (int)($request->input('total_rows', 10));
        $totalCols = (int)($request->input('total_columns', 16));

        DB::beginTransaction();
        try {
            $room = new Room();
            $room->cinema_id = $cinema->id;
            $room->name = $request->name;
            $room->total_rows = $totalRows;
            $room->total_columns = $totalCols;
            $room->capacity = 0;
            $room->save();

            // Generate default layout and seats:
            // Rows A, B... J
            // Rows 1 to 4: Standard, Rows 5 to 8: VIP, Last 2 rows: Standard/Couple
            $layout = [];
            $validCount = 0;
            $rowLetters = range('A', 'Z');

            for ($r = 0; $r < $totalRows; $r++) {
                $rowLetter = $rowLetters[$r] ?? ('R' . ($r + 1));
                $rowCells = [];
                $seatNumberInRow = 1;

                for ($c = 0; $c < $totalCols; $c++) {
                    // Create central aisle for aesthetics (e.g. columns 4 and 11 empty if wide)
                    $isAisle = false;

                    // Seat type
                    $type = 'standard';
                    if ($r >= 4 && $r <= 7) {
                        $type = 'vip';
                    } elseif ($r === $totalRows - 1) {
                        $type = 'sweetbox';
                    }

                    if (!$isAisle) {
                        $rowCells[] = [
                            'type' => $type,
                            'row' => $rowLetter,
                            'number' => $seatNumberInRow,
                            'code' => $rowLetter . $seatNumberInRow,
                        ];

                        Seat::create([
                            'room_id' => $room->id,
                            'row' => $rowLetter,
                            'number' => $seatNumberInRow,
                            'type' => $type,
                            'col_index' => $c + 1,
                        ]);

                        $seatNumberInRow++;
                        $validCount++;
                    } else {
                        $rowCells[] = [
                            'type' => 'empty',
                            'row' => $rowLetter,
                            'number' => 0,
                            'code' => '',
                        ];
                    }
                }
                $layout[] = $rowCells;
            }

            $room->layout_data = json_encode($layout);
            $room->capacity = $validCount;
            $room->save();

            DB::commit();
            return redirect()->back()->with('success', "Thêm phòng chiếu '{$room->name}' thành công ({$validCount} ghế)!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi tạo phòng chiếu: ' . $e->getMessage());
        }
    }

    /**
     * Update room details.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $room->name = $request->name;
        $room->save();

        return redirect()->back()->with('success', "Cập nhật phòng '{$room->name}' thành công!");
    }

    /**
     * Delete room.
     */
    public function destroy(Room $room)
    {
        if ($room->showtimes()->count() > 0) {
            return redirect()->back()->with('error', "Không thể xóa phòng '{$room->name}' đang có lịch chiếu!");
        }

        $room->seats()->delete();
        $room->delete();

        return redirect()->back()->with('success', "Đã xóa phòng chiếu thành công!");
    }

    /**
     * API: Get room layout data.
     */
    public function getLayout(Room $room)
    {
        $cinema = $room->cinema;
        $layout = json_decode($room->layout_data, true);

        // If no layout saved yet, generate from existing seats
        if (!$layout || empty($layout)) {
            $seats = $room->seats()->orderBy('row')->orderBy('number')->get();
            $grouped = [];
            foreach ($seats as $seat) {
                $grouped[$seat->row][] = [
                    'type' => $seat->type ?? 'standard',
                    'row' => $seat->row,
                    'number' => $seat->number,
                    'code' => $seat->row . $seat->number,
                    'col_index' => $seat->col_index,
                ];
            }
            $layout = array_values($grouped);
        }

        return response()->json([
            'success' => true,
            'room' => [
                'id' => $room->id,
                'name' => $room->name,
                'cinema_name' => $cinema ? $cinema->name : '',
                'capacity' => $room->capacity,
                'total_rows' => $room->total_rows ?? count($layout),
                'total_columns' => $room->total_columns ?? (isset($layout[0]) ? count($layout[0]) : 16),
                'layout' => $layout,
            ]
        ]);
    }

    /**
     * API: Save room layout from the Grid Designer.
     */
    public function saveLayout(Request $request, Room $room)
    {
        $request->validate([
            'total_rows' => 'required|integer|min:3|max:30',
            'total_columns' => 'required|integer|min:4|max:40',
            'grid' => 'required|array',
        ]);

        $totalRows = (int)$request->input('total_rows');
        $totalCols = (int)$request->input('total_columns');
        $grid = $request->input('grid');

        DB::beginTransaction();
        try {
            // Delete old seats for this room
            $room->seats()->delete();

            $validCount = 0;
            $savedLayout = [];

            foreach ($grid as $rIdx => $rowCells) {
                $savedRow = [];
                foreach ($rowCells as $cIdx => $cell) {
                    $type = $cell['type'] ?? 'empty';
                    $rowLetter = $cell['row'] ?? chr(65 + $rIdx);
                    $seatNumber = (int)($cell['number'] ?? 0);
                    $code = $cell['code'] ?? ($type !== 'empty' ? "{$rowLetter}{$seatNumber}" : '');

                    $savedRow[] = [
                        'type' => $type,
                        'row' => $rowLetter,
                        'number' => $seatNumber,
                        'code' => $code,
                    ];

                    if ($type !== 'empty' && $seatNumber > 0) {
                        Seat::create([
                            'room_id' => $room->id,
                            'row' => $rowLetter,
                            'number' => $seatNumber,
                            'type' => $type,
                            'col_index' => $cIdx + 1,
                        ]);
                        $validCount++;
                    }
                }
                $savedLayout[] = $savedRow;
            }

            $room->total_rows = $totalRows;
            $room->total_columns = $totalCols;
            $room->layout_data = json_encode($savedLayout);
            $room->capacity = $validCount;
            $room->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Đã lưu bố cục thành công! Tổng số ghế: {$validCount}",
                'capacity' => $validCount,
                'room_id' => $room->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lưu bố cục: ' . $e->getMessage(),
            ], 500);
        }
    }
}
