<?php

namespace Tests\Feature;

use App\Models\Promotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionExpirationTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_voucher_is_hidden_from_promotions_page()
    {
        // Active promotion
        $activePromo = Promotion::create([
            'code' => 'ACTIVEVOUCHER',
            'title' => 'Ưu Đãi Đang Hoạt Động',
            'description' => 'Mô tả ưu đãi',
            'discount_amount' => 50000,
            'points_required' => 0,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(5),
        ]);

        // Expired promotion
        $expiredPromo = Promotion::create([
            'code' => 'EXPIREDVOUCHER',
            'title' => 'Ưu Đãi Đã Hết Hạn',
            'description' => 'Mô tả voucher hết hạn',
            'discount_amount' => 30000,
            'points_required' => 0,
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDay(),
        ]);

        $response = $this->get(route('promotions.index'));

        $response->assertStatus(200);
        $response->assertSee('ACTIVEVOUCHER');
        $response->assertSee('Ưu Đãi Đang Hoạt Động');
        $response->assertDontSee('EXPIREDVOUCHER');
        $response->assertDontSee('Ưu Đãi Đã Hết Hạn');
    }

    public function test_when_admin_updates_expiration_date_voucher_reappears()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Expired promotion
        $promo = Promotion::create([
            'code' => 'REAPPEARPROMO',
            'title' => 'Ưu Đãi Tái Xuất Hiện',
            'description' => 'Mô tả tái xuất hiện',
            'discount_percent' => 15,
            'points_required' => 0,
            'start_date' => now()->subDays(10)->format('Y-m-d'),
            'end_date' => now()->subDay()->format('Y-m-d'),
            'image_url' => 'https://example.com/test.jpg',
        ]);

        // Before update: Hidden
        $responseBefore = $this->get(route('promotions.index'));
        $responseBefore->assertDontSee('REAPPEARPROMO');

        // Admin updates end_date to 15 days in the future
        $responseUpdate = $this->actingAs($admin)->put(route('admin.promotions.update', $promo->id), [
            'code' => 'REAPPEARPROMO',
            'title' => 'Ưu Đãi Tái Xuất Hiện',
            'description' => 'Mô tả tái xuất hiện',
            'discount_percent' => 15,
            'points_required' => 0,
            'start_date' => now()->subDays(10)->format('Y-m-d'),
            'end_date' => now()->addDays(15)->format('Y-m-d'),
            'image_url' => 'https://example.com/test.jpg',
        ]);

        $responseUpdate->assertRedirect(route('admin.promotions.index'));

        // After update: Voucher reappears on /promotions page!
        $responseAfter = $this->get(route('promotions.index'));
        $responseAfter->assertStatus(200);
        $responseAfter->assertSee('REAPPEARPROMO');
        $responseAfter->assertSee('Ưu Đãi Tái Xuất Hiện');
    }

    public function test_admin_promotions_index_shows_correct_status_badges()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Promotion::create([
            'code' => 'STATUSACTIVE',
            'title' => 'Voucher Hoạt Động',
            'description' => 'Test',
            'points_required' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
        ]);

        Promotion::create([
            'code' => 'STATUSEXPIRED',
            'title' => 'Voucher Hết Hạn',
            'description' => 'Test',
            'points_required' => 0,
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDay(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.promotions.index'));

        $response->assertStatus(200);
        $response->assertSee('Đang hiển thị');
        $response->assertSee('Hết hạn (Đã ẩn)');
    }
}
