<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\Room;

class CinemaController extends Controller
{
    public function index()
    {
        $cinemas = Cinema::with(['rooms' => function($q) {
            $q->withCount('seats')->orderBy('name', 'asc');
        }])->withCount('rooms')->get();
        return view('admin.cinemas.index', compact('cinemas'));
    }

    public function create()
    {
        return view('admin.cinemas.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $cinema = new Cinema();
        $cinema->name = $request->name;
        $cinema->location = $request->location;
        $cinema->save();

        return redirect()->route('admin.cinemas.index')->with('success', 'Thêm rạp thành công!');
    }

    public function edit(Cinema $cinema)
    {
        return view('admin.cinemas.form', compact('cinema'));
    }

    public function update(Request $request, Cinema $cinema)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $cinema->name = $request->name;
        $cinema->location = $request->location;
        $cinema->save();

        return redirect()->route('admin.cinemas.index')->with('success', 'Cập nhật rạp thành công!');
    }

    public function destroy(Cinema $cinema)
    {
        if ($cinema->rooms()->count() > 0) {
            return redirect()->route('admin.cinemas.index')->with('error', 'Không thể xóa rạp đang có phòng chiếu!');
        }

        $cinema->delete();
        return redirect()->route('admin.cinemas.index')->with('success', 'Xóa rạp thành công!');
    }
}
