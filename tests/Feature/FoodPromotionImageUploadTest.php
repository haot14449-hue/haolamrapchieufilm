<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Food;
use App\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class FoodPromotionImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::firstOrCreate(
            ['email' => 'admin@hctv.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => 'admin']
        );
    }

    public function test_admin_can_create_food_with_uploaded_file()
    {
        $file = UploadedFile::fake()->create('popcorn.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admin.foods.store'), [
            'name' => 'Bắp Phô Mai VIP',
            'description' => 'Bắp rang vị phô mai thượng hạng',
            'price' => 75000,
            'image_file' => $file,
        ]);

        $response->assertRedirect(route('admin.foods.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('food', [
            'name' => 'Bắp Phô Mai VIP',
            'price' => 75000,
        ]);

        $food = Food::where('name', 'Bắp Phô Mai VIP')->first();
        $this->assertStringStartsWith('http', $food->image_url);
        $this->assertStringContainsString('uploads/foods/food_', $food->image_url);

        // Clean up created file if exists
        $rawPath = public_path(ltrim($food->getRawOriginal('image_url'), '/'));
        if (file_exists($rawPath)) {
            @unlink($rawPath);
        }
    }

    public function test_admin_can_create_food_with_url()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.foods.store'), [
            'name' => 'Nước Ngọt Lớn',
            'description' => 'Ly 32oz',
            'price' => 35000,
            'image_url' => 'https://images.unsplash.com/photo-coke',
        ]);

        $response->assertRedirect(route('admin.foods.index'));
        $this->assertDatabaseHas('food', [
            'name' => 'Nước Ngọt Lớn',
            'image_url' => 'https://images.unsplash.com/photo-coke',
        ]);
    }

    public function test_admin_can_update_food_with_new_uploaded_file()
    {
        $food = Food::create([
            'name' => 'Bắp Rang Cũ',
            'price' => 50000,
            'image_url' => 'https://example.com/old.jpg',
        ]);

        $newFile = UploadedFile::fake()->create('new_popcorn.png', 100, 'image/png');

        $response = $this->actingAs($this->admin)->put(route('admin.foods.update', $food->id), [
            'name' => 'Bắp Rang Mới',
            'price' => 55000,
            'image_file' => $newFile,
        ]);

        $response->assertRedirect(route('admin.foods.index'));
        $food->refresh();
        $this->assertEquals('Bắp Rang Mới', $food->name);
        $this->assertStringContainsString('uploads/foods/food_', $food->getRawOriginal('image_url'));

        // Clean up created file
        $rawPath = public_path(ltrim($food->getRawOriginal('image_url'), '/'));
        if (file_exists($rawPath)) {
            @unlink($rawPath);
        }
    }

    public function test_admin_can_create_promotion_with_uploaded_file()
    {
        $file = UploadedFile::fake()->create('promo_banner.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admin.promotions.store'), [
            'code' => 'TET2026',
            'title' => 'Ưu Đãi Tết Rộn Ràng',
            'description' => 'Giảm giá vé xem phim dịp Tết',
            'discount_percent' => 20,
            'points_required' => 50,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(15)->format('Y-m-d'),
            'image_file' => $file,
        ]);

        $response->assertRedirect(route('admin.promotions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('promotions', [
            'code' => 'TET2026',
            'title' => 'Ưu Đãi Tết Rộn Ràng',
        ]);

        $promo = Promotion::where('code', 'TET2026')->first();
        $this->assertStringContainsString('uploads/promotions/promo_', $promo->image_url);

        // Clean up created file
        $rawPath = public_path(ltrim($promo->getRawOriginal('image_url'), '/'));
        if (file_exists($rawPath)) {
            @unlink($rawPath);
        }
    }
}
