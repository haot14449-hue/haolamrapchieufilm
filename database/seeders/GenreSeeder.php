<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            ['name' => 'Hành động', 'description' => 'Phim chứa nhiều pha hành động kịch tính, rượt đuổi, chiến đấu'],
            ['name' => 'Hài hước', 'description' => 'Phim mang lại tiếng cười, tình huống hài hước dí dỏm'],
            ['name' => 'Hoạt hình', 'description' => 'Phim hoạt hình 2D, 3D phù hợp nhiều lứa tuổi'],
            ['name' => 'Viễn tưởng', 'description' => 'Khoa học viễn tưởng, công nghệ tương lai, vũ trụ'],
            ['name' => 'Phiêu lưu', 'description' => 'Khám phá những vùng đất mới, hành trình mạo hiểm'],
            ['name' => 'Kinh dị', 'description' => 'Hồi hộp rùng rợn, giật gân, thế lực bí ẩn'],
            ['name' => 'Tình cảm', 'description' => 'Lãng mạn, tình yêu đôi lứa ngọt ngào và cảm xúc'],
            ['name' => 'Tâm lý', 'description' => 'Khai thác chiều sâu tâm lý nhân vật, bi kịch xã hội'],
            ['name' => 'Giật gân', 'description' => 'Căng thẳng, hồi hộp, bất ngờ đến phút chót'],
            ['name' => 'Gia đình', 'description' => 'Phim ấm áp, ý nghĩa dành cho mọi thành viên trong gia đình'],
            ['name' => 'Võ thuật', 'description' => 'Các màn võ thuật đẹp mắt, kungfu, quyền anh'],
            ['name' => 'Âm nhạc', 'description' => 'Phim ca nhạc, vũ đạo và giai điệu ấn tượng'],
            ['name' => 'Tội phạm', 'description' => 'Phá án, trinh thám, đối đầu băng đảng mafia'],
            ['name' => 'Bí ẩn', 'description' => 'Các vụ án ly kỳ, bí ẩn chưa có lời giải'],
            ['name' => 'Tài liệu', 'description' => 'Phim tư liệu lịch sử, khoa học và đời sống thực'],
        ];

        foreach ($genres as $g) {
            Genre::firstOrCreate(
                ['name' => $g['name']],
                ['slug' => Str::slug($g['name']), 'description' => $g['description']]
            );
        }
    }
}
