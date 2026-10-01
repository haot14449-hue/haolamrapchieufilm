<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\MovieActor;
use App\Models\Room;
use App\Models\Showtime;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MoreMoviesSeeder extends Seeder
{
    public function run(): void
    {
        $moviesData = [
            [
                'title' => 'Dune: Hành Tinh Cát - Phần Hai',
                'description' => 'Paul Atreides kết hợp cùng Chani và tộc người Fremen trong cuộc chiến sinh tử nhằm trả thù những kẻ đã âm mưu tiêu diệt gia tộc mình. Đứng trước ngã rẽ giữa tình yêu của đời mình và vận mệnh của cả vũ trụ, anh phải nỗ lực ngăn chặn một tương lai đen tối tàn khốc mà chỉ mình anh có thể thấy trước.',
                'genre' => 'Khoa Học, Viễn tưởng, Hành động, Phiêu lưu',
                'duration' => 166,
                'release_date' => '2026-03-01',
                'poster_url' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=600&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=Way9Dexny3w',
                'actors' => [
                    [
                        'name' => 'Timothée Chalamet',
                        'role' => 'Paul Atreides',
                        'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nam diễn viên người Mỹ gốc Pháp từng nhận đề cử giải Oscar, Quả cầu vàng và BAFTA.',
                        'order' => 1,
                    ],
                    [
                        'name' => 'Zendaya',
                        'role' => 'Chani',
                        'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Ngôi sao đa tài từng đoạt 2 giải Primetime Emmy và Quả cầu vàng.',
                        'order' => 2,
                    ],
                    [
                        'name' => 'Austin Butler',
                        'role' => 'Feyd-Rautha Harkonnen',
                        'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nam diễn viên đoạt giải Quả cầu vàng, gây ấn tượng mạnh với vai phản diện Feyd-Rautha tàn bạo.',
                        'order' => 3,
                    ],
                    [
                        'name' => 'Rebecca Ferguson',
                        'role' => 'Lady Jessica',
                        'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nữ diễn viên Thụy Điển nổi tiếng qua loạt phim Mission: Impossible và Dune.',
                        'order' => 4,
                    ],
                ],
            ],
            [
                'title' => 'Oppenheimer',
                'description' => 'Khắc họa cuộc đời đầy biến động và giằng xé của nhà vật lý lý thuyết J. Robert Oppenheimer, người đứng đầu Dự án Manhattan tối mật chịu trách nhiệm phát triển bom nguyên tử đầu tiên trong lịch sử nhân loại, mở ra kỷ nguyên hạt nhân nhưng cũng đầy ân hận lương tâm.',
                'genre' => 'Tâm lý, Giật gân, Tài liệu',
                'duration' => 180,
                'release_date' => '2026-04-15',
                'poster_url' => 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?auto=format&fit=crop&w=600&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=uYPbbksJxIg',
                'actors' => [
                    [
                        'name' => 'Cillian Murphy',
                        'role' => 'J. Robert Oppenheimer',
                        'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nam tài tử người Ireland xuất sắc giành giải Oscar 2024 cho Nam diễn viên chính xuất sắc nhất.',
                        'order' => 1,
                    ],
                    [
                        'name' => 'Emily Blunt',
                        'role' => 'Katherine "Kitty" Oppenheimer',
                        'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nữ minh tinh người Anh đoạt giải Quả cầu vàng và được đề cử giải Oscar.',
                        'order' => 2,
                    ],
                    [
                        'name' => 'Robert Downey Jr.',
                        'role' => 'Lewis Strauss',
                        'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Huyền thoại Hollywood đoạt giải Oscar cho Nam diễn viên phụ xuất sắc nhất qua vai Lewis Strauss.',
                        'order' => 3,
                    ],
                ],
            ],
            [
                'title' => 'Mai',
                'description' => 'Tác phẩm điện ảnh tâm lý xã hội sâu sắc của đạo diễn Trấn Thành, xoay quanh Mai - một người phụ nữ làm nghề trị liệu massage gần 40 tuổi với số phận chìm nổi và quá khứ đầy tổn thương. Khi tình yêu đích thực với chàng nhạc công trẻ tuổi Dương chớm nở, những định kiến xã hội khắt khe một lần nữa thử thách giới hạn hạnh phúc.',
                'genre' => 'Tâm lý, Tình cảm, Gia đình',
                'duration' => 131,
                'release_date' => '2026-05-10',
                'poster_url' => 'https://images.unsplash.com/photo-1518173946687-a4c8a383392e?auto=format&fit=crop&w=600&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1200&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=0hWq5UfI8lE',
                'actors' => [
                    [
                        'name' => 'Phương Anh Đào',
                        'role' => 'Mai',
                        'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nữ diễn viên điện ảnh tài năng hàng đầu Việt Nam, được đánh giá cao về lối diễn xuất biến hóa nội tâm.',
                        'order' => 1,
                    ],
                    [
                        'name' => 'Tuấn Trần',
                        'role' => 'Dương (Sâu)',
                        'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nam diễn viên triển vọng của màn ảnh Việt, ghi dấu ấn qua nhiều tác phẩm điện ảnh doanh thu trăm tỷ.',
                        'order' => 2,
                    ],
                    [
                        'name' => 'Trấn Thành',
                        'role' => 'Ông Hoàng (Bố Mai)',
                        'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Đạo diễn, MC và diễn viên tài hoa đạt nhiều kỷ lục doanh thu phòng vé nhất lịch sử điện ảnh Việt Nam.',
                        'order' => 3,
                    ],
                ],
            ],
            [
                'title' => 'Godzilla x Kong: Đế Chế Mới',
                'description' => 'Cuộc chiến hoành tráng đưa hai biểu tượng quái thú vĩ đại nhất lịch sử là Kong dũng mãnh và Godzilla bất khả chiến bại bắt tay hợp lực chống lại một mối đe dọa khổng lồ chưa từng được khám phá ẩn sâu trong lòng Trái Đất rỗng, thách thức sự tồn vong của toàn nhân loại.',
                'genre' => 'Hành động, Khoa Học, Viễn tưởng, Giật gân',
                'duration' => 115,
                'release_date' => '2026-06-01',
                'poster_url' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=600&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=1200&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=qqrpMRDuPfc',
                'actors' => [
                    [
                        'name' => 'Rebecca Hall',
                        'role' => 'Dr. Ilene Andrews',
                        'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nữ diễn viên người Anh xuất sắc với các vai diễn trong MonsterVerse và Iron Man 3.',
                        'order' => 1,
                    ],
                    [
                        'name' => 'Brian Tyree Henry',
                        'role' => 'Bernie Hayes',
                        'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nam diễn viên Mỹ từng nhận đề cử Oscar và giải thưởng Emmy danh giá.',
                        'order' => 2,
                    ],
                    [
                        'name' => 'Dan Stevens',
                        'role' => 'Trapper',
                        'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Ngôi sao người Anh ghi dấu với loạt vai diễn trong Beauty and the Beast và Legion.',
                        'order' => 3,
                    ],
                ],
            ],
            [
                'title' => 'Exhuma: Quật Mộ Trùng Ma',
                'description' => 'Hai pháp sư trừ tà trẻ tuổi đầy tài năng được một gia tộc giàu có ở Mỹ thuê để giải cứu dòng họ khỏi căn bệnh bí hiểm. Cùng với một thầy phong thủy và một chuyên gia tang lễ, họ thực hiện khai quật một ngôi mộ cổ đại tại Hàn Quốc, vô tình đánh thức một thế lực tà ác khủng khiếp.',
                'genre' => 'Kinh dị, Bí ẩn, Giật gân',
                'duration' => 134,
                'release_date' => '2026-07-20',
                'poster_url' => 'https://images.unsplash.com/photo-1509248961158-e54f6934749c?auto=format&fit=crop&w=600&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1200&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=kYvW_8Tfv5Q',
                'actors' => [
                    [
                        'name' => 'Choi Min-sik',
                        'role' => 'Kim Sang-deok (Thầy phong thủy)',
                        'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Tượng đài diễn xuất của điện ảnh Hàn Quốc, ngôi sao của kiệt tác Oldboy và Đại Thủy Chiến.',
                        'order' => 1,
                    ],
                    [
                        'name' => 'Kim Go-eun',
                        'role' => 'Lee Hwa-rim (Pháp sư)',
                        'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nữ diễn viên tài sắc vẹn toàn từng đạt giải Baeksang với màn hóa thân xuất thần.',
                        'order' => 2,
                    ],
                    [
                        'name' => 'Lee Do-hyun',
                        'role' => 'Yoon Bong-gil',
                        'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Gương mặt nam diễn viên trẻ triển vọng bậc nhất xứ Hàn qua The Glory và Exhuma.',
                        'order' => 3,
                    ],
                ],
            ],
            [
                'title' => 'Kung Fu Panda 4',
                'description' => 'Sau bao chiến tích lẫy lừng, Chú gấu trúc Po được giao trọng trách trở thành Thủ lĩnh Tinh thần của Thung lũng Bình Yên và cần tìm một Hiệp sĩ Rồng kế vị. Tuy nhiên, sự xuất hiện của mụ phù thủy biến hình Tắc Kè Bông xảo quyệt buộc Po cùng cáo siêu trộm Zhen dấn thân vào chuyến phiêu lưu nghẹt thở.',
                'genre' => 'Hoạt hình, Hài hước, Gia đình, Hành động',
                'duration' => 94,
                'release_date' => '2026-08-15',
                'poster_url' => 'https://images.unsplash.com/photo-1563089145-599997674d42?auto=format&fit=crop&w=600&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=1200&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=_inKs4eeHiI',
                'actors' => [
                    [
                        'name' => 'Jack Black',
                        'role' => 'Po (Lồng tiếng)',
                        'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nam danh hài, diễn viên lồng tiếng huyền thoại của nhân vật gấu trúc Po nổi tiếng toàn cầu.',
                        'order' => 1,
                    ],
                    [
                        'name' => 'Awkwafina',
                        'role' => 'Zhen (Lồng tiếng)',
                        'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nữ diễn viên, rapper tài năng từng đoạt giải Quả cầu vàng.',
                        'order' => 2,
                    ],
                    [
                        'name' => 'Viola Davis',
                        'role' => 'The Chameleon (Lồng tiếng)',
                        'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Một trong số ít nghệ sĩ đạt danh hiệu cao quý EGOT (Emmy, Grammy, Oscar, Tony).',
                        'order' => 3,
                    ],
                ],
            ],
            [
                'title' => 'Interstellar: Hố Đen Tử Thần',
                'description' => 'Khi tài nguyên Trái Đất cạn kiệt và loài người đứng trước nguy cơ tuyệt chủng, cựu phi công Cooper cùng một nhóm nhà khoa học dũng cảm bước vào chuyến du hành liên thiên hà qua một lỗ sâu vũ trụ kỳ bí, vượt qua nghịch lý thời gian và không gian để tìm kiếm ngôi nhà mới cho nhân loại.',
                'genre' => 'Khoa Học, Viễn tưởng, Phiêu lưu, Tâm lý',
                'duration' => 169,
                'release_date' => '2026-09-01',
                'poster_url' => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=600&q=80',
                'backdrop_url' => 'https://images.unsplash.com/photo-1506703719100-a0f3a48c0f86?auto=format&fit=crop&w=1200&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=zSWdZVtXT7E',
                'actors' => [
                    [
                        'name' => 'Matthew McConaughey',
                        'role' => 'Joseph Cooper',
                        'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nam diễn viên đoạt giải Oscar nổi tiếng với phong cách diễn xuất cuốn hút và giàu chiều sâu cảm xúc.',
                        'order' => 1,
                    ],
                    [
                        'name' => 'Anne Hathaway',
                        'role' => 'Dr. Amelia Brand',
                        'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Nữ minh tinh đoạt giải Oscar từng góp mặt trong hàng loạt siêu phẩm Hollywood kinh điển.',
                        'order' => 2,
                    ],
                    [
                        'name' => 'Jessica Chastain',
                        'role' => 'Murphy "Murph" Cooper',
                        'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                        'bio' => 'Chủ nhân giải thưởng Oscar danh giá, khẳng định tài năng qua các vai diễn nữ khoa học gia xuất chúng.',
                        'order' => 3,
                    ],
                ],
            ],
        ];

        $rooms = Room::all();
        if ($rooms->isEmpty()) {
            return;
        }

        $now = Carbon::now();

        foreach ($moviesData as $mData) {
            $actors = $mData['actors'] ?? [];
            unset($mData['actors']);

            $movie = Movie::updateOrCreate(
                ['title' => $mData['title']],
                $mData
            );

            // Seed actors
            foreach ($actors as $actorData) {
                MovieActor::updateOrCreate(
                    ['movie_id' => $movie->id, 'name' => $actorData['name']],
                    $actorData
                );
            }

            // Create Showtimes for today, tomorrow, and the next few days
            $formats = ['2D', '3D', 'IMAX 2D'];
            $languages = ['Phụ Đề', 'Lồng Tiếng'];
            $prices = [90000, 110000, 130000, 150000];

            for ($dayOffset = 0; $dayOffset <= 3; $dayOffset++) {
                $targetDate = $now->copy()->addDays($dayOffset);

                // Add 2-3 showtimes per day across rooms
                $hours = [10, 14, 18, 20];
                foreach ($hours as $hour) {
                    $startTime = $targetDate->copy()->setHour($hour)->setMinute(15)->setSecond(0);
                    
                    // Don't create past showtimes for today
                    if ($startTime->isPast()) {
                        $startTime = $now->copy()->addHours(2 + $hour % 4);
                    }

                    $randomRoom = $rooms->random();
                    $format = $randomRoom->name === 'IMAX 1' || str_contains($randomRoom->name, 'IMAX') ? 'IMAX 2D' : $formats[array_rand($formats)];
                    $lang = $languages[array_rand($languages)];
                    $price = $prices[array_rand($prices)];

                    Showtime::updateOrCreate(
                        [
                            'movie_id' => $movie->id,
                            'room_id' => $randomRoom->id,
                            'start_time' => $startTime->toDateTimeString(),
                        ],
                        [
                            'price' => $price,
                            'format' => $format,
                            'language' => $lang,
                        ]
                    );
                }
            }
        }

        // Add some realistic initial sales for Dune & Mai so they feature on Leaderboard
        $customer = User::where('role', 'customer')->first() ?? User::first();
        if ($customer) {
            $dune = Movie::where('title', 'like', '%Dune%')->first();
            $mai = Movie::where('title', 'like', '%Mai%')->first();
            $kungfu = Movie::where('title', 'like', '%Kung Fu Panda%')->first();

            $seedSales = [
                ['movie' => $dune, 'ticketCount' => 6, 'pricePerTicket' => 120000, 'daysAgo' => 1],
                ['movie' => $mai, 'ticketCount' => 5, 'pricePerTicket' => 100000, 'daysAgo' => 2],
                ['movie' => $kungfu, 'ticketCount' => 2, 'pricePerTicket' => 90000, 'daysAgo' => 0],
            ];

            foreach ($seedSales as $sale) {
                if (!$sale['movie']) continue;
                $st = Showtime::where('movie_id', $sale['movie']->id)->first();
                if (!$st) continue;

                $room = $st->room;
                $seats = $room ? $room->seats()->take($sale['ticketCount'])->get() : collect();
                if ($seats->count() < $sale['ticketCount']) continue;

                $total = $sale['ticketCount'] * $sale['pricePerTicket'];
                $bookingDate = Carbon::now()->subDays($sale['daysAgo'])->setHour(19)->setMinute(30);

                // Create paid booking
                $booking = Booking::create([
                    'user_id' => $customer->id,
                    'showtime_id' => $st->id,
                    'original_price' => $total,
                    'total_price' => $total,
                    'status' => 'paid',
                    'created_at' => $bookingDate,
                    'updated_at' => $bookingDate,
                ]);

                foreach ($seats as $seat) {
                    Ticket::create([
                        'booking_id' => $booking->id,
                        'seat_id' => $seat->id,
                        'status' => 'paid',
                        'created_at' => $bookingDate,
                        'updated_at' => $bookingDate,
                    ]);
                }
            }
        }
    }
}
