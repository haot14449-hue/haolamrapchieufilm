<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\MovieActor;

class ActorSeeder extends Seeder
{
    public function run(): void
    {
        $dune = Movie::where('title', 'like', '%DUNE%')->first();
        if ($dune) {
            $duneActors = [
                [
                    'name' => 'Timothée Chalamet',
                    'role' => 'Paul Atreides',
                    'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                    'bio' => "Timothée Hal Chalamet (sinh ngày 27 tháng 12 năm 1995) là một nam diễn viên người Mỹ gốc Pháp. Anh nhận được nhiều giải thưởng danh giá bao gồm đề cử giải Oscar, hai giải Quả cầu vàng và ba giải BAFTA.",
                    'order' => 1
                ],
                [
                    'name' => 'Zendaya',
                    'role' => 'Chani',
                    'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                    'bio' => "Zendaya Maree Stoermer Coleman (sinh ngày 1 tháng 9 năm 1996) là một nữ diễn viên, ca sĩ kiêm người mẫu nổi tiếng người Mỹ, từng đoạt 2 giải Primetime Emmy và Quả cầu vàng.",
                    'order' => 2
                ],
                [
                    'name' => 'Rebecca Ferguson',
                    'role' => 'Lady Jessica',
                    'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
                    'bio' => "Rebecca Louisa Ferguson Sundström là một nữ diễn viên Thụy Điển tài năng, ghi dấu ấn lớn qua loạt bom tấn Mission: Impossible, The Greatest Showman và Dune.",
                    'order' => 3
                ],
                [
                    'name' => 'Austin Butler',
                    'role' => 'Feyd-Rautha Harkonnen',
                    'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                    'bio' => "Austin Butler (sinh ngày 17 tháng 8 năm 1991) là nam diễn viên người Mỹ nổi tiếng với vai diễn Elvis Presley từng đoạt giải Quả cầu vàng và vai phản diện Feyd-Rautha trong Dune 2.",
                    'order' => 4
                ]
            ];

            foreach ($duneActors as $actorData) {
                MovieActor::updateOrCreate(
                    ['movie_id' => $dune->id, 'name' => $actorData['name']],
                    $actorData
                );
            }
        }

        $oppenheimer = Movie::where('title', 'like', '%OPPENHEIMER%')->first();
        if ($oppenheimer) {
            $oppenheimerActors = [
                [
                    'name' => 'Cillian Murphy',
                    'role' => 'J. Robert Oppenheimer',
                    'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
                    'bio' => "Cillian Murphy (sinh ngày 25 tháng 5 năm 1976) là nam diễn viên người Ireland. Anh xuất sắc giành giải Oscar cho Nam diễn viên chính xuất sắc nhất năm 2024 qua vai nhà vật lý học Oppenheimer.",
                    'order' => 1
                ],
                [
                    'name' => 'Emily Blunt',
                    'role' => 'Katherine "Kitty" Oppenheimer',
                    'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                    'bio' => "Emily Olivia Leah Blunt là nữ diễn viên người Anh tài năng từng đoạt giải Quả cầu vàng và nhận đề cử giải Oscar cho vai diễn trong Oppenheimer.",
                    'order' => 2
                ],
                [
                    'name' => 'Robert Downey Jr.',
                    'role' => 'Lewis Strauss',
                    'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
                    'bio' => "Robert John Downey Jr. là nam diễn viên huyền thoại Hollywood, đoạt giải Oscar cho Nam diễn viên phụ xuất sắc nhất qua vai Lewis Strauss và nổi tiếng toàn cầu với nhân vật Tony Stark / Iron Man.",
                    'order' => 3
                ]
            ];

            foreach ($oppenheimerActors as $actorData) {
                MovieActor::updateOrCreate(
                    ['movie_id' => $oppenheimer->id, 'name' => $actorData['name']],
                    $actorData
                );
            }
        }

        $batman = Movie::where('title', 'like', '%BATMAN%')->first();
        if ($batman) {
            $batmanActors = [
                [
                    'name' => 'Robert Pattinson',
                    'role' => 'Bruce Wayne / Batman',
                    'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
                    'bio' => "Robert Douglas Thomas Pattinson (sinh ngày 13 tháng 5 năm 1986) là nam diễn viên người Anh xuất sắc với phong cách diễn xuất nội tâm đỉnh cao trong The Batman của Matt Reeves.",
                    'order' => 1
                ],
                [
                    'name' => 'Zoë Kravitz',
                    'role' => 'Selina Kyle / Catwoman',
                    'avatar' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=400&q=80',
                    'bio' => "Zoë Isabella Kravitz là nữ diễn viên, ca sĩ kiêm người mẫu người Mỹ, thủ vai Miêu Nữ Selina Kyle đầy quyến rũ và bí ẩn.",
                    'order' => 2
                ]
            ];

            foreach ($batmanActors as $actorData) {
                MovieActor::updateOrCreate(
                    ['movie_id' => $batman->id, 'name' => $actorData['name']],
                    $actorData
                );
            }
        }
    }
}
