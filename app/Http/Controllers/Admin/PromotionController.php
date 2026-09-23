<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderBy('end_date', 'desc')->get();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promotions,code|max:50',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'points_required' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image_file' => 'nullable|file|max:5120',
            'image_url' => 'nullable|string',
        ]);

        if (!$request->hasFile('image_file') && !$request->filled('image_url')) {
            return back()->withInput()->withErrors(['image_url' => 'Vui lòng chọn ảnh từ máy tính hoặc nhập Link ảnh minh họa!']);
        }

        $promotion = new Promotion();
        $promotion->code = strtoupper($request->code);
        $promotion->title = $request->title;
        $promotion->description = $request->description;
        $promotion->discount_percent = $request->discount_percent;
        $promotion->discount_amount = $request->discount_amount;
        $promotion->points_required = $request->points_required;
        $promotion->start_date = $request->start_date;
        $promotion->end_date = $request->end_date;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'promo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/promotions'), $filename);
            $promotion->image_url = '/uploads/promotions/' . $filename;
        } else {
            $promotion->image_url = $request->image_url;
        }

        $promotion->save();

        return redirect()->route('admin.promotions.index')->with('success', 'Thêm khuyến mãi thành công!');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.form', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'points_required' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image_file' => 'nullable|file|max:5120',
            'image_url' => 'nullable|string',
        ]);

        $promotion->code = strtoupper($request->code);
        $promotion->title = $request->title;
        $promotion->description = $request->description;
        $promotion->discount_percent = $request->discount_percent;
        $promotion->discount_amount = $request->discount_amount;
        $promotion->points_required = $request->points_required;
        $promotion->start_date = $request->start_date;
        $promotion->end_date = $request->end_date;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'promo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/promotions'), $filename);
            $promotion->image_url = '/uploads/promotions/' . $filename;
        } elseif ($request->filled('image_url')) {
            $promotion->image_url = $request->image_url;
        }

        $promotion->save();

        return redirect()->route('admin.promotions.index')->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Xóa khuyến mãi thành công!');
    }
}
