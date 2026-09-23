<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cinema;

class CinemaSeeder extends Seeder
{
    public function run(): void
    {
        $cinemas = [
            [
                'name' => 'Cineme Quốc Thanh (TP.HCM)',
                'location' => '271 Nguyễn Trãi, Phường Nguyễn Cư Trinh, Quận 1, TP. Hồ Chí Minh',
                'city' => 'TP.HCM',
                'latitude' => 10.7634800,
                'longitude' => 106.6859500,
                'phone' => '028 7300 8888',
            ],
            [
                'name' => 'Cineme Huế (TP.HUẾ)',
                'location' => '25 Hai Bà Trưng, Phường Vĩnh Ninh, Thành phố Huế',
                'city' => 'Thừa Thiên Huế',
                'latitude' => 16.4637000,
                'longitude' => 107.5908000,
                'phone' => '0234 7300 888',
            ],
            [
                'name' => 'Cineme Hai Bà Trưng (TP.HCM)',
                'location' => '135 Hai Bà Trưng, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
                'city' => 'TP.HCM',
                'latitude' => 10.7816000,
                'longitude' => 106.6987000,
                'phone' => '028 7300 9999',
            ],
            [
                'name' => 'Cineme Sinh Viên (Bình Dương)',
                'location' => 'Đường N1, Khu Đô Thị ĐHQG-HCM, Đông Hòa, Dĩ An, Bình Dương',
                'city' => 'Bình Dương',
                'latitude' => 10.8752000,
                'longitude' => 106.8005000,
                'phone' => '0274 7300 888',
            ],
            [
                'name' => 'Cineme Kiên Giang (Rạch Sỏi)',
                'location' => 'TTTM Rạch Sỏi, Mai Thị Hồng Hạnh, Vĩnh Hòa Hiệp, Châu Thành, Kiên Giang',
                'city' => 'Kiên Giang',
                'latitude' => 9.9654000,
                'longitude' => 105.1189000,
                'phone' => '0297 7300 888',
            ],
            [
                'name' => 'HCTV Cinematic (Hà Nội)',
                'location' => '33 Xuân Thủy, Phường Dịch Vọng Hậu, Quận Cầu Giấy, Hà Nội',
                'city' => 'Hà Nội',
                'latitude' => 21.0362000,
                'longitude' => 105.7836000,
                'phone' => '024 7300 8888',
            ],
            [
                'name' => 'HCTV Landmark (Đà Nẵng)',
                'location' => 'Đường Bạch Đằng, Phường Hải Châu 1, Quận Hải Châu, Đà Nẵng',
                'city' => 'Đà Nẵng',
                'latitude' => 16.0678000,
                'longitude' => 108.2208000,
                'phone' => '0236 7300 888',
            ],
        ];

        // Update cinema 1 if exists, or create
        $firstCinema = Cinema::find(1);
        if ($firstCinema) {
            $firstCinema->update($cinemas[0]);
            array_shift($cinemas);
        }

        foreach ($cinemas as $c) {
            Cinema::updateOrCreate(
                ['name' => $c['name']],
                $c
            );
        }
    }
}
