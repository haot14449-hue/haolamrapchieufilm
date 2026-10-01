<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::active()->orderBy('created_at', 'desc')->get();
        return view('promotions.index', compact('promotions'));
    }
}
