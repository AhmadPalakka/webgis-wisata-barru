<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        Destination::truncate();
        
        $destinations = [
            [
                'name' => 'Pantai Tanjung Asap',
                'category' => 'Wisata-alam',
                'description' => 'sebuah wisata alam berupa air terjun yang dikelilingi hutan tropis yang rimbun. Air terjun jatuh dari tebing batu berlapis, menciptakan aliran air putih yang kontras dengan warna hijau jernih kolam di bawahnya. Tebing bebatuan terlihat kokoh dan bertekstur alami, sementara pepohonan dan tanaman merambat tumbuh subur di sekitarnya, menambah suasana asri.',
                'address' => 'Desa Palakkka, Kec. Barru, Barru',
                'contact' => '+62',
                'latitude' => -4.4486701,
                'longitude' => 119.7320524,
                'main_image' => 'tanjungasap.webp',
                'gallery' => [
                    'pantai_tanjung_bastian_1.jpg',
                    'pantai_tanjung_bastian_2.jpg',
                    'pantai_tanjung_bastian_sunset.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '24 jam',
                'activities' => [
                    'Bersantai & Berpiknik',
                    'Bermain Air & Berenang',
                    'Berfoto',
                ],
                'visitor_info' => 'Air terjun ini didominasi oleh wisatawan lokal dan domestik, serta biasa dikunjungi pelancong dari luar.'
            ],
            [
                'name' => 'Bukit Maddo',
                'category' => 'wisata-alam',
                'description' => 'Bukit Tuamese menawarkan pemandangan dari ketinggian yang menakjubkan dengan suasana tenang dan udara sejuk. Lokasi ini sangat populer untuk hiking dan fotografi landscape. Dari puncaknya, pengunjung dapat melihat hamparan hijau TTU dan sunset yang indah.',
                'address' => 'Tellumpanua, Kec. Tanete Rilau, Barru',
                'contact' => '+62',
                'latitude' => -4.4761679,
                'longitude' => 119.6339444,
                'main_image' => 'bukit_tuamese.jpg',
                'gallery' => [
                    'bukit_tuamese_view.jpg',
                    'bukit_tuamese_hiking.jpg',
                    'bukit_tuamese_sunset.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '06:00 - 18:00',
                'activities' => [
                    'Hiking & Trekking',
                    'Fotografi Landscape',
                    'Menikmati Pemandangan',
                    'Camping',
                    'Bird Watching'
                ],
                'visitor_info' => 'Destinasi favorit para pecinta alam dan fotografer. Disarankan membawa air minum dan perlengkapan hiking yang memadai.'
            ],
            [
                'name' => 'Pantai Laguna',
                'category' => 'pantai',
                'description' => 'Telaga Biru TTU atau Tua Kolo adalah fenomena alam unik berupa tiga mata air di perbatasan TTU–Timor Leste. Keunikan telaga ini terletak pada perubahan warna air yang terjadi secara alami, dari biru jernih hingga kehijauan tergantung kondisi cuaca dan musim.',
                'address' => 'Pao pao, Kec. Tanete Rilau, Barru',
                'latitude' => -4.4922416,
                'longitude' => 119.5895686,
                'main_image' => 'telaga_biru_tua_kolo.png',
                'gallery' => [
                    'telaga_biru_blue.jpg',
                    'telaga_biru_green.jpg',
                    'telaga_biru_aerial.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '07:00 - 17:00',
                'activities' => [
                    'Fotografi Alam',
                    'Eksplorasi Geologi',
                    'Observasi Fenomena Alam',
                    'Edukasi Lingkungan'
                ],
                'visitor_info' => 'Wajib menjaga kebersihan dan menghormati adat istiadat lokal. Tidak diperbolehkan berenang karena telaga memiliki nilai sakral bagi masyarakat setempat.'
            ],
            [
                'name' => 'MT. Kappire',
                'category' => 'wisata-alam',
                'description' => 'menampilkan panorama wisata alam pegunungan yang hijau dan memukau. Hamparan bukit-bukit curam dikelilingi vegetasi lebat, menciptakan pemandangan yang segar dan alami. Kontur perbukitan yang berlekuk-lekuk memberikan kesan dramatis.',
                'address' => 'Palakka, Kec. Barru, Barru',
                'latitude' => -4.4755502,
                'longitude' => 119.7379861,
                // 'latitude' => -9.25000,
                // 'longitude' => 124.40000,
                'main_image' => 'kappire2.jpeg',
                'gallery' => [
                    'kappire1.jpeg',
                    'kappire2.jpeg',
                    'kappire3.jpeg'
                ],
                'entry_fees' => [
                    'Motor' => 15000,
                    'Mobil' => 25000
                ],
                'operating_hours' => '24 Jam',
                'activities' => [
                    'Mendaki',
                    'Menikmati Sunset',
                    'Fotografi Air Terjun',
                    'Camping'
                ],
                'visitor_info' => ''
            ],
            [
                'name' => 'Rumah Adat Saoraja Lapinceng',
                'category' => 'sejarah',
                'description' => 'Gereja bersejarah dengan arsitektur kolonial yang masih terawat baik. Dibangun pada masa penjajahan Belanda dan menjadi saksi bisu perkembangan agama Kristen di TTU.',
                'address' => 'Balusu, Kec. Balusu, Barru',
                'latitude' => -4.2878845,
                'longitude' => 119.6475939,
                // 'latitude' => -9.45123,
                // 'longitude' => 124.47856,
                'main_image' => 'kappire.jpeg',
                'gallery' => [
                    'gereja_fatumnasi_interior.jpg',
                    'gereja_fatumnasi_altar.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '06:00 - 18:00',
                'activities' => [
                    'Wisata Sejarah',
                    'Fotografi Arsitektur',
                    'Ziarah',
                    'Ibadah'
                ],
                'visitor_info' => 'Pengunjung diharapkan menghormati kesucian tempat ibadah dan berpakaian sopan.'
            ],
            [
                'name' => 'Museum Colliq Pujie',
                'category' => 'Sejarah',
                'description' => 'Rumah adat tradisional yang menampilkan arsitektur dan budaya lokal TTU. Sonaf merupakan rumah tradisional suku Atoni yang memiliki nilai filosofis dan spiritual yang tinggi.',
                'address' => 'Alun alun Kota, Kec. Barru, Barru',
                'latitude' => -4.4143089,
                'longitude' => 119.6147837,
                'main_image' => 'rumah_adat_sonaf.jpg',
                'gallery' => [
                    'sonaf_interior.jpg',
                    'sonaf_ceremony.jpg'
                ],
                'entry_fees' => [
                    'dewasa' => 10000,
                    'anak' => 5000
                ],
                'operating_hours' => '08:00 - 17:00',
                'activities' => [
                    'Wisata Budaya',
                    'Edukasi Tradisi',
                    'Fotografi',
                    'Workshop Tenun'
                ],
                'visitor_info' => 'Pengunjung dapat melihat langsung kehidupan tradisional dan kerajinan tenun khas TTU.'
            ]
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}