<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use App\Models\MovieActor;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieSearchSuggestionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Genre::create(['name' => 'Hành động', 'description' => 'Phim hành động kịch tính']);
        Genre::create(['name' => 'Tâm lý', 'description' => 'Phim tâm lý chiều sâu']);

        $movie1 = Movie::create([
            'title' => 'Oppenheimer',
            'description' => 'Câu chuyện về cha đẻ bom nguyên tử J. Robert Oppenheimer.',
            'poster_url' => 'https://example.com/oppenheimer.jpg',
            'backdrop_url' => 'https://example.com/oppenheimer_bg.jpg',
            'trailer_url' => 'https://youtube.com',
            'duration' => 180,
            'release_date' => '2023-07-21',
            'genre' => 'Tâm lý, Lịch sử',
        ]);

        MovieActor::create([
            'movie_id' => $movie1->id,
            'name' => 'Cillian Murphy',
            'role' => 'J. Robert Oppenheimer',
            'bio' => 'Nam diễn viên xuất sắc người Ireland.',
            'order' => 0,
        ]);

        $movie2 = Movie::create([
            'title' => 'The Batman',
            'description' => 'Hiệp sĩ bóng đêm bảo vệ thành phố Gotham.',
            'poster_url' => 'https://example.com/batman.jpg',
            'backdrop_url' => 'https://example.com/batman_bg.jpg',
            'trailer_url' => 'https://youtube.com',
            'duration' => 176,
            'release_date' => '2022-03-04',
            'genre' => 'Hành động, Tội phạm',
        ]);

        MovieActor::create([
            'movie_id' => $movie2->id,
            'name' => 'Robert Pattinson',
            'role' => 'Bruce Wayne / Batman',
            'bio' => 'Nam diễn viên nổi tiếng.',
            'order' => 0,
        ]);
    }

    public function test_suggest_api_returns_matching_movies()
    {
        $response = $this->getJson(route('movies.suggest', ['q' => 'Batman']));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'movies' => [
                '*' => ['id', 'title', 'poster_url', 'genre', 'duration', 'year', 'url']
            ],
            'actors'
        ]);

        $data = $response->json();
        $this->assertCount(1, $data['movies']);
        $this->assertEquals('The Batman', $data['movies'][0]['title']);
    }

    public function test_suggest_api_returns_matching_actors()
    {
        $response = $this->getJson(route('movies.suggest', ['q' => 'Cillian']));

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertNotEmpty($data['actors']);
        $this->assertEquals('Cillian Murphy', $data['actors'][0]['name']);
        $this->assertEquals('Oppenheimer', $data['actors'][0]['movie_title']);
    }

    public function test_suggest_api_filters_by_genre()
    {
        // Search 'Robert' matches both Oppenheimer (in role) and Batman (Robert Pattinson).
        // If filtered by 'Hành động', only The Batman should be returned.
        $response = $this->getJson(route('movies.suggest', [
            'q' => 'Robert',
            'genre' => 'Hành động'
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertCount(1, $data['movies']);
        $this->assertEquals('The Batman', $data['movies'][0]['title']);
    }

    public function test_movies_index_filters_by_search_keyword()
    {
        $response = $this->get(route('movies.index', ['search' => 'Gotham']));

        $response->assertStatus(200);
        $response->assertSee('The Batman');
        $response->assertDontSee('Oppenheimer');
    }

    public function test_movies_index_filters_by_genre()
    {
        $response = $this->get(route('movies.index', ['genre' => 'Tâm lý']));

        $response->assertStatus(200);
        $response->assertSee('Oppenheimer');
        $response->assertDontSee('The Batman');
    }

    public function test_empty_search_returns_empty_suggestions()
    {
        $response = $this->getJson(route('movies.suggest'));

        $response->assertStatus(200);
        $response->assertJson([
            'movies' => [],
            'actors' => [],
        ]);
    }
}
