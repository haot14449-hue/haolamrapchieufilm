<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotions = [
            [
                'code' => 'WELCOME2026',
                'title' => 'Giảm 20% cho thành viên mới',
                'description' => 'Áp dụng cho mọi lần mua vé đầu tiên tại HCTV Cinema.',
                'discount_percent' => 20,
                'discount_amount' => null,
                'points_required' => 0,
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->addMonths(3)->endOfMonth(),
                'image_url' => 'https://images.unsplash.com/photo-1542204165-65bf26472b9b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'code' => 'COMBOBAP50',
                'title' => 'Giảm 50.000đ Combo Bắp Nước',
                'description' => 'Áp dụng cho mọi hóa đơn đặt vé có mua kèm Combo Bắp Nước bất kỳ tại cụm rạp HCTV Cinema.',
                'discount_percent' => null,
                'discount_amount' => 50000,
                'points_required' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(4),
                'image_url' => 'https://images.unsplash.com/photo-1572177191856-3cde618dee1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'code' => 'HCTVHSSV',
                'title' => 'Ưu đãi 15% Học Sinh - Sinh Viên',
                'description' => 'Đặc quyền giảm 15% giá vé cho học sinh sinh viên tất cả các suất chiếu từ Thứ 2 đến Thứ 6.',
                'discount_percent' => 15,
                'discount_amount' => null,
                'points_required' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'image_url' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'code' => 'CINEWEEKEND',
                'title' => 'Giảm 30.000đ Suất Chiếu Cuối Tuần',
                'description' => 'Cuối tuần xem phim bom tấn cực đã, giảm ngay 30.000đ cho đơn hàng vào Thứ 7 & Chủ Nhật.',
                'discount_percent' => null,
                'discount_amount' => 30000,
                'points_required' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'image_url' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'code' => 'POPCORNFREE',
                'title' => 'Giảm 25.000đ Bắp Rang Bơ Size L',
                'description' => 'Tận hưởng bắp phô mai và bắp ngọt thơm lừng giòn tan với voucher giảm giá 25.000đ.',
                'discount_percent' => null,
                'discount_amount' => 25000,
                'points_required' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(5),
                'image_url' => 'https://images.unsplash.com/photo-1585670146399-5287f3944fb4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'code' => 'POINT500',
                'title' => 'Đổi 50 điểm lấy voucher 50k',
                'description' => 'Dành cho khách hàng thân thiết có tích lũy điểm thưởng thành viên HCTV.',
                'discount_percent' => null,
                'discount_amount' => 50000,
                'points_required' => 50,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'image_url' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
            ],
        ];

        foreach ($promotions as $promo) {
            DB::table('promotions')->updateOrInsert(
                ['code' => $promo['code']],
                array_merge($promo, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // Give User 3 (HAO TRAN VAN) and others some initial saved vouchers
        $users = \App\Models\User::all();
        $welcomePromo = \App\Models\Promotion::where('code', 'WELCOME2026')->first();
        $comboPromo = \App\Models\Promotion::where('code', 'COMBOBAP50')->first();
        $studentPromo = \App\Models\Promotion::where('code', 'HCTVHSSV')->first();

        foreach ($users as $u) {
            if ($welcomePromo) {
                \App\Models\UserVoucher::firstOrCreate([
                    'user_id' => $u->id,
                    'promotion_id' => $welcomePromo->id,
                ], [
                    'is_used' => false,
                    'saved_at' => now(),
                ]);
            }
            if ($comboPromo) {
                \App\Models\UserVoucher::firstOrCreate([
                    'user_id' => $u->id,
                    'promotion_id' => $comboPromo->id,
                ], [
                    'is_used' => false,
                    'saved_at' => now(),
                ]);
            }
        }
    }
}
