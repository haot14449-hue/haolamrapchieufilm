<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_genres_list()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hctv.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        Genre::create(['name' => 'Hành động', 'description' => 'Phim hành động đỉnh cao']);

        $response = $this->actingAs($admin)->get(route('admin.genres.index'));
        $response->assertStatus(200);
        $response->assertSee('Hành động');
        $response->assertSee('Quản Lý Thể Loại Phim');
    }

    public function test_admin_can_create_new_genre()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hctv.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $response = $this->actingAs($admin)->post(route('admin.genres.store'), [
            'name' => 'Chiến tranh',
            'description' => 'Phim về đề tài chiến trường khốc liệt',
        ]);

        $response->assertRedirect(route('admin.genres.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('genres', [
            'name' => 'Chiến tranh',
            'slug' => 'chien-tranh',
        ]);
    }

    public function test_admin_can_update_genre_and_sync_movies()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hctv.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $genre = Genre::create(['name' => 'Kinh dị cũ', 'description' => 'Mô tả']);
        $movie = Movie::create([
            'title' => 'Test Movie',
            'description' => 'Mô tả phim',
            'trailer_url' => 'https://youtube.com',
            'duration' => 90,
            'release_date' => '2026-01-01',
            'genre' => 'Kinh dị cũ, Phiêu lưu',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.genres.update', $genre->id), [
            'name' => 'Kinh dị mới',
            'description' => 'Mô tả mới',
        ]);

        $response->assertRedirect(route('admin.genres.index'));
        $this->assertDatabaseHas('genres', ['name' => 'Kinh dị mới']);

        $movie->refresh();
        $this->assertStringContainsString('Kinh dị mới', $movie->genre);
    }

    public function test_admin_can_delete_genre()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hctv.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $genre = Genre::create(['name' => 'Tài liệu cổ']);

        $response = $this->actingAs($admin)->delete(route('admin.genres.destroy', $genre->id));
        $response->assertRedirect(route('admin.genres.index'));

        $this->assertDatabaseMissing('genres', ['id' => $genre->id]);
    }
}
