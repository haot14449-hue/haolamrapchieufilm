<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;
use App\Models\MovieActor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieActorManagementTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_create_movie_with_file_upload_genres_and_actors()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hctv.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $poster = UploadedFile::fake()->create('poster.jpg', 100, 'image/jpeg');
        $backdrop = UploadedFile::fake()->create('backdrop.jpg', 200, 'image/jpeg');
        $actorAvatar = UploadedFile::fake()->create('actor1.jpg', 50, 'image/jpeg');

        $response = $this->actingAs($admin)->post(route('admin.movies.store'), [
            'title' => 'Avengers: Secret Wars',
            'description' => 'Trận chiến đa vũ trụ đỉnh cao của các siêu anh hùng Marvel.',
            'trailer_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => 180,
            'release_date' => '2027-05-01',
            'genres' => ['Hành động', 'Viễn tưởng', 'Phiêu lưu'],
            'custom_genre' => 'Siêu anh hùng',
            'poster_file' => $poster,
            'backdrop_file' => $backdrop,
            'actors' => [
                [
                    'name' => 'Robert Downey Jr.',
                    'role' => 'Doctor Doom / Tony Stark',
                    'avatar_file' => $actorAvatar,
                    'bio' => 'Trở lại vũ trụ Marvel với vai diễn phản diện huyền thoại Doctor Doom.'
                ],
                [
                    'name' => 'Tom Holland',
                    'role' => 'Peter Parker / Spider-Man',
                    'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400',
                    'bio' => 'Người Nhện trẻ tuổi chiến đấu bảo vệ đa vũ trụ.'
                ]
            ]
        ]);

        $response->assertRedirect(route('admin.movies.index'));
        $response->assertSessionHas('success');

        $movie = Movie::where('title', 'Avengers: Secret Wars')->first();
        $this->assertNotNull($movie);
        $this->assertStringContainsString('Hành động', $movie->genre);
        $this->assertStringContainsString('Viễn tưởng', $movie->genre);
        $this->assertStringContainsString('Siêu anh hùng', $movie->genre);

        // Check actors
        $this->assertEquals(2, $movie->actors()->count());
        $actor1 = $movie->actors()->where('name', 'Robert Downey Jr.')->first();
        $this->assertNotNull($actor1);
        $this->assertEquals('Doctor Doom / Tony Stark', $actor1->role);
        $this->assertStringContainsString('/uploads/actors/', $actor1->avatar);

        $actor2 = $movie->actors()->where('name', 'Tom Holland')->first();
        $this->assertNotNull($actor2);
        $this->assertEquals('https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400', $actor2->avatar);

        // Test customer detail page renders actors
        $detailResponse = $this->get(route('movies.show', $movie->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Avengers: Secret Wars');
        $detailResponse->assertSee('Doctor Doom / Tony Stark');
        $detailResponse->assertSee('Tom Holland');
        $detailResponse->assertSee('actor-modal');
    }

    public function test_admin_can_update_movie_and_edit_actors()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hctv.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $movie = Movie::create([
            'title' => 'Sample Movie',
            'description' => 'Original description',
            'trailer_url' => 'https://youtube.com',
            'duration' => 100,
            'release_date' => '2026-01-01',
            'genre' => 'Hài hước',
            'poster_url' => 'https://via.placeholder.com/500',
            'backdrop_url' => 'https://via.placeholder.com/1000',
        ]);

        $actor = MovieActor::create([
            'movie_id' => $movie->id,
            'name' => 'Old Actor',
            'role' => 'Old Role',
            'avatar' => 'https://via.placeholder.com/100',
            'bio' => 'Old Bio',
            'order' => 0,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.movies.update', $movie->id), [
            'title' => 'Updated Movie Title',
            'description' => 'Updated description',
            'trailer_url' => 'https://youtube.com',
            'duration' => 120,
            'release_date' => '2026-02-01',
            'genres' => ['Hành động', 'Kinh dị'],
            'poster_url' => 'https://via.placeholder.com/500_new',
            'backdrop_url' => 'https://via.placeholder.com/1000_new',
            'actors' => [
                [
                    'id' => $actor->id,
                    'name' => 'Updated Actor Name',
                    'role' => 'Updated Character',
                    'avatar_url' => 'https://via.placeholder.com/100_updated',
                    'bio' => 'Updated Bio'
                ],
                [
                    'name' => 'New Actor 2',
                    'role' => 'New Character 2',
                    'avatar_url' => 'https://via.placeholder.com/100_new',
                    'bio' => 'New Actor Bio'
                ]
            ]
        ]);

        $response->assertRedirect(route('admin.movies.index'));
        $movie->refresh();

        $this->assertEquals('Updated Movie Title', $movie->title);
        $this->assertStringContainsString('Hành động', $movie->genre);
        $this->assertStringContainsString('Kinh dị', $movie->genre);
        $this->assertEquals(2, $movie->actors()->count());

        $updatedActor = $movie->actors()->find($actor->id);
        $this->assertEquals('Updated Actor Name', $updatedActor->name);
        $this->assertEquals('Updated Character', $updatedActor->role);
    }
}
