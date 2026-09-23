<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::orderBy('created_at', 'desc')->get();
        return view('admin.foods.index', compact('foods'));
    }

    public function create()
    {
        return view('admin.foods.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_file' => 'nullable|file|max:5120',
            'image_url' => 'nullable|string',
        ]);

        if (!$request->hasFile('image_file') && !$request->filled('image_url')) {
            return back()->withInput()->withErrors(['image_url' => 'Vui lòng chọn ảnh từ máy tính hoặc nhập Link ảnh minh họa!']);
        }

        $food = new Food();
        $food->name = $request->name;
        $food->description = $request->description;
        $food->price = $request->price;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'food_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/foods'), $filename);
            $food->image_url = '/uploads/foods/' . $filename;
        } else {
            $food->image_url = $request->image_url;
        }

        $food->save();

        return redirect()->route('admin.foods.index')->with('success', 'Thêm đồ ăn/combo thành công!');
    }

    public function edit(Food $food)
    {
        return view('admin.foods.form', compact('food'));
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_file' => 'nullable|file|max:5120',
            'image_url' => 'nullable|string',
        ]);

        $food->name = $request->name;
        $food->description = $request->description;
        $food->price = $request->price;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'food_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/foods'), $filename);
            $food->image_url = '/uploads/foods/' . $filename;
        } elseif ($request->filled('image_url')) {
            $food->image_url = $request->image_url;
        }

        $food->save();

        return redirect()->route('admin.foods.index')->with('success', 'Cập nhật đồ ăn/combo thành công!');
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return redirect()->route('admin.foods.index')->with('success', 'Xóa đồ ăn/combo thành công!');
    }
}
