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
                'address' => 'Desa Palakka, Kec. Barru, Barru',

                'latitude' => -4.4486701,
                'longitude' => 119.7294775,
                'main_image' => 'tanjungasap.webp',
                'gallery' => [
                    'tanjungasap1.jpg',
                    'tanjungasap2.jpg',
                    'tanjungasap3.webp'
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
                'description' => 'Bukit Maddo adalah salah satu destinasi wisata alam unggulan di Kabupaten Barru, Sulawesi Selatan, yang menawarkan panorama menakjubkan dari ketinggian. Dari puncaknya, pengunjung disuguhi pemandangan hamparan perbukitan hijau yang berpadu dengan aliran sungai berkelok di tengah lembah, serta latar pegunungan yang membentang megah di kejauhan.',
                'address' => 'Tellumpanua, Kec. Tanete Rilau, Barru',
                'contact' => '',
                'latitude' => -4.4761679,
                'longitude' => 119.6339444,
                'main_image' => 'bukitmaddo.webp',
                'gallery' => [
                    'maddo1.jpg',
                    'maddo2.jpg',
                    'maddo3.webp'
                ],
                'entry_fees' => null,
                'operating_hours' => '06:00 - 18:00',
                'activities' => [
                    'Hiking & Trekking',
                    'Fotografi Landscape',
                    'Menikmati Pemandangan',
                    'Camping'
                ],
                'visitor_info' => 'Destinasi favorit para pecinta alam dan fotografer.'
            ],
            [
                'name' => 'Pantai Laguna',
                'category' => 'pantai',
                'description' => 'Pantai Laguna adalah destinasi wisata bahari di Kabupaten Barru, Sulawesi Selatan, yang menawarkan suasana tenang dengan hamparan pasir cokelat lembut dan deretan pohon kelapa yang berjajar rapi di tepi pantai. Air laut yang tenang berpadu dengan semilir angin sepoi-sepoi menciptakan nuansa nyaman bagi pengunjung yang ingin bersantai. Di sepanjang pantai terdapat gazebo sederhana yang dapat digunakan untuk beristirahat sambil menikmati pemandangan laut lepas.',
                'address' => 'Pao pao, Kec. Tanete Rilau, Barru',
                'latitude' => -4.4914567535148855,
                'longitude' => 119.59450410722599,
                'main_image' => 'pantailaguna.jpg',
                'gallery' => [
                    '.jpg',
                    '.jpg',
                    '.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '07:00 - 17:00',
                'activities' => [
                    'Fotografi',
                    'piknik',
                    'berenang',
                    'menikmati sunset'
                ],
                'visitor_info' => 'Menjaga pantai agar tetap bersih .'
            ],
            [
                'name' => 'MT. Kappire',
                'category' => 'wisata-alam',
                'description' => 'menampilkan panorama wisata alam pegunungan yang hijau dan memukau. Hamparan bukit-bukit curam dikelilingi vegetasi lebat, menciptakan pemandangan yang segar dan alami. Kontur perbukitan yang berlekuk-lekuk memberikan kesan dramatis.',
                'address' => 'Palakka, Kec. Barru, Barru',
                'latitude' => -4.4755502,
                'longitude' => 119.7379861,
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
                'description' => 'Rumah Adat Lapinceng merupakan peninggalan Kerajaan Soppeng Riaja. Fungsi utamanya adalah sebagai kediaman raja Kerajaan Soppeng Riaja. Bentuknya adalah rumah panggung. Rumah Adat Lapinceng merupakan tempat persiapan perang antara Kerajaan Soppeng Riaja dan pasukan Belanda pada Perang Balusu tahun 1905. Rumah ini hanya ditinggali oleh keturunan Raja Kerajaan Soppeng Riaja dari garis keturunan dengan Kerajaan Soppeng.',
                'address' => 'Balusu, Kec. Balusu, Barru',
                'latitude' => -4.2878845,
                'longitude' => 119.6475939,
                // 'latitude' => -9.45123,
                // 'longitude' => 124.47856,
                'main_image' => 'lapinceng.jpg',
                'gallery' => [
                    'gereja_fatumnasi_interior.jpg',
                    'gereja_fatumnasi_altar.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '06:00 - 18:00',
                'activities' => [
                    'Wisata Sejarah',
                    'Fotografi Arsitektur',
                ],
                'visitor_info' => 'Pengunjung diharapkan Menjaga peninggalan sejarah ini'
            ],
            [
                'name' => 'Museum Colliq Pujie',
                'category' => 'sejarah',
                'description' => 'Museum Colliq Pujie adalah museum yang berlokasi di Kabupaten Barru, Sulawesi Selatan. Lokasi tepatnya berada di Alun-Alun Colliq Pujie di Kota Barru. Penamaan Colliq Pujie ini berasal dari tokoh sejarah yang menjadi ikon Kota Barru. Museum ini dikelola oleh Dinas Pendidikan Kabupaten Barru dan menjadi salah satu aset daerah.',
                'address' => 'Alun alun Kota, Kec. Barru, Barru',
                'latitude' => -4.414078961306621,
                'longitude' => 119.61935417973089,
                'main_image' => 'colliqpijie1.jpg',
                'gallery' => [
                    'colliqpujie2.jpeg',
                    'colliqpujie3.webp'
                ],
                'entry_fees' => null,
                'operating_hours' => '08:00 - 17:00',
                'activities' => [
                    'Wisata sejarah',
                    'Edukasi sejarah',
                    'Fotografi',

                ],
                'visitor_info' => 'Pengunjung dapat melihat langsung beberapa koleksi sejarah.'
            ],
            [
                'name' => 'Danau PakuE',
                'category' => 'wisata-alam',
                'description' => 'Danau Pakue yang terletak di Desa Nepo, Kabupaten Barru, Sulawesi Selatan, merupakan destinasi wisata alam yang menawarkan suasana asri dan menenangkan. Dikelilingi pepohonan hijau yang rimbun, danau ini menghadirkan panorama alami yang indah dengan airnya yang tenang serta memantulkan bayangan langit dan hutan di sekitarnya. Suasananya masih alami dan jauh dari keramaian, sehingga sangat cocok untuk bersantai, atau bahkan berkemah di tepi danau.',
                'address' => 'nepo, Kec. Mallusetasi, Barru',
                'latitude' => -4.160736335491223,
                'longitude' => 119.68498779751262,
                'main_image' => 'danau pakue3.jpg',
                'gallery' => [
                    'danaupakue1.jpg',
                    'danau pakue3.jpg',
                    'danaupakue2.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '24 Jam',
                'activities' => [
                    'Bersantai',
                    'Camping',
                    'Fotografi',
                ],
                'visitor_info' => 'Pengunjung diharapkan Untuk memiliki kondisi fisik yang prima'
            ],
            [
                'name' => 'Komplek Makam Arung Nepo',
                'category' => 'sejarah',
                'description' => 'Makam raja-raja Nepo berdasarkan tahun Hijriayh sudah berusia sekitar 122 tahun (1897–2019), dengan ciri makam tersendiri yaitu adanya nisan dipasang pada bagian tengahnya atau pada bagian kepala yang dimakamkan, sehingga nisan tersebut memiliki arti dan kedudukan yang sangat penting. Arti penting dari pemakaian nisan tersebut tidak terlepas dari pengaruh tradisi megalit. Makam yang mendapat pengaruh megalitik memiliki unsur-unsur tradisi megalitik yang tertuang dalam pahatan dan bangunan sakral, memakai batu alam menyerupai menhir atau bentuk patung yang sederhana. Keadaan tersebut mencerminkan berlangsungnya tradisi megalitik dalam masyarakat saat itu. .',
                'address' => 'Manuba, Kec. Mallusetasi, Barru',
                'latitude' => -4.207115847090191,
                'longitude' => 119.65942635333194,
                'main_image' => 'makamnepo1.jpg',
                'gallery' => [
                    'makamnepo1.jpg',
                    'makamnepo2.jpg',
                    'makamnepo3.webp'
                ],
                'entry_fees' => null,
                'operating_hours' => '08:00-17:00',
                'activities' => [
                    'Edukasi',
                    'Penelitian',
                    'Fotografi',
                ],
                'visitor_info' => 'Pengunjung diharapkan Untuk menjaga kelestarian lingkungan'
            ],
            [
                'name' => 'Bujung Mattimboe',
                'category' => 'wisata-alam',
                'description' => 'Bujung Mattimboe adalah salah satu daya tarik wisata di Desa Nepo, Kecamatan Mallusetasi, Kabupaten Barru. Daya tarik utama Bujung Mattimboe terletak pada keasrian alamnya. Air terjun yang mengalir dari pegunungan Desa Nepo, dijadikan tempat berendam atau sekadar bermain air. Di sekitarnya, bebatuan besar yang tersebar menjadi spot favorit untuk berfoto.',
                'address' => 'Nepo, Kec. Mallusetasi, Barru',
                'latitude' => -4.1888612425561425,
                'longitude' => 119.67542682449559,
                'main_image' => 'mattimboe3.jpg',
                'gallery' => [
                    'mattimboe1.jpg',
                    'mattimboe3.jpg',
                    'mattimboe2.jpg'
                ],
                'entry_fees' => [
                    'Motor' => 15000,
                    'Mobil' => 25000
                ],
                'operating_hours' => '08:00-17:00',
                'activities' => [
                    'Piknik',
                    'Bersantai',
                    'Bermain Air',
                    'Fotografi',
                ],
                'visitor_info' => 'Pengunjung diharapkan Untuk Selalu menjaga kebersihan'
            ],
            [
                'name' => 'Manuba',
                'category' => 'wisata-alam',
                'description' => 'Air terjun ini memiliki tinggi kurang lebih 12 meter. Bentuknya tampak seperti tirai yang jatuh di antara bebatuan dan dikelilingi oleh pepohonan hijau.Di bagian bawah air terjun terdapat kolam berukuran besar yang bisa digunakan untuk berenang dengan airnya yang jernih berwarna hijau kebiruan. Banyak wisatawan yang datang untuk berenang maupun sekadar bermain air di kolam yang ada di bawah air terjun tersebut.',
                'address' => 'Manuba, Kec. Mallusetasi, Barru',
                'latitude' => -4.239309371255582,
                'longitude' => 119.67106878041386,
                'main_image' => 'manuba1.jpg',
                'gallery' => [
                    'manuba1.jpg',
                    'manuba3.jpg',
                    'manuba2.jpg'
                ],
                'entry_fees' => [
                    'Perorang' => 5000,
                ],
                'operating_hours' => '08:00-17:00',
                'activities' => [
                    'Berenang',
                    'Bersantai',
                    'Piknik',
                    'Fotografi',
                ],
                'visitor_info' => 'Pengunjung harus berhati hati dengan batu yang licin '
            ],
            [
                'name' => 'Pulau Dutungan',
                'category' => 'pantai',
                'description' => 'Pulau Dutungan merupakan destinasi wisata yang berada di kabupaten Barru, Sulawesi Selatan. Pulau ini memiliki keindahan pasir putih dengan pesona bawah laut yang sangat indah. Banyaknya pepohonan rindang tumbuh di tepi pantai membuat hawanya cukup sejuk. Terdapat banyak resort yang dapat dipilih untuk menginap pulau ini. beragam aktivitas wisata pun dapat dilakukan pada destinasi wisata ini. Terutama aktivitas wisata laut dan pantai.',
                'address' => 'Cilellang, Kec. Mallusetasi, Barru',
                'latitude' => -4.178522580737749,
                'longitude' => 119.61852498950617,
                'main_image' => 'dutungan.jpg',
                'gallery' => [
                    'dutungan1.jpg',
                    'dutungan3.jpg',
                    'dutungan22.jpeg',
                    'dutungan2.jpg'
                ],
                'entry_fees' => [
                    'Hari Libur' => 30000,
                    'Hari Biasa' => 50000
                ],
                'operating_hours' => '08:00-18:00',
                'activities' => [
                    'Camping',
                    'Bersantai',
                    'Bermain Air',
                    'Fotografi',
                ],
                'visitor_info' => 'Diharapkan untuk tetap menjaga kebersihan pantai dengan tidak buang sampah sembarangan. '
            ],
            [
                'name' => 'Monumen Paccekke',
                'category' => 'sejarah',
                'description' => 'Desa Paccekkke merupakan Saksi sejarah perjuangan Panglima Besar Jenderal Soedirman di Tanah Sulawesi, dimana di Desa ini Lahirnya TRI Divisi Hasanuddin dan Kodam XIV Hasanuddin, itu semua termuat dalam surat perintah (mandat) Jenderal Soedirman yang di tulis menggunakan ejaan lama yang kemudian di abadikan dengan prasasti yang berada di depan Monumen Paccekke. Sedangkan monumen paccekke sendiri untuk mengabadikan 4 resimen yang tergabung pada saat itu, yakni : Resimen I Paccekke, Resimen II PKR Luwuk, Resimen III Bajeng Makassar Selatan, dan Resimen IV PKR Kolaka Kendari melakukan konferensi yang mencetuskan lahirnya TRI Devisi Hasanuddin.',
                'address' => 'Paccekke, Kec. Soppeng Riaja, Barru',
                'latitude' => -4.2604397574958845,
                'longitude' => 119.70053556682373,
                'main_image' => 'paccekke2.jpg',
                'gallery' => [
                    'paccekke.jpg',
                    'paccekke1.jpg',
                    'paccekke3.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '08:00-18:00',
                'activities' => [
                    'Edukasi',
                    'Fotografi'
                ],
                'visitor_info' => 'Diharapkan untuk tetap menjaga kebersihan dengan tidak buang sampah sembarangan. '
            ],
            [
                'name' => 'Celebes Canyon',
                'category' => 'wisata-alam',
                'description' => 'wisata ini sudah cukup dikenal sebagai tempat wisata yang alami. Disebut sebagai Celebes Canyon karena mirip dengan wisata Grand Canyon yang ada di Amerika. Destinasi baru ini ada di Sulawesi Selatan yang mana orang-orang setempat akhirnya memberi nama Celebes Canyon. Tidak hanya wisata alam saja tetapi juga di daerah sekitar Kabupaten Barru ternyata sering dimanfaatkan sebagai tempat rekreasi anak-anak muda.',
                'address' => 'Libureng, Kec. Tanete Riaja, Barru',
                'latitude' => -4.50067121557752,
                'longitude' => 119.71627310915288,
                'main_image' => 'celebes.jpg',
                'gallery' => [
                    'celebes2.jpeg',
                    'celebes1.jpg',
                    'celebes3.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '08:00-18:00',
                'activities' => [
                    'Bersantai',
                    'Berenang',
                    'Bermain Air',
                    'Fotografi'
                ],
                'visitor_info' => 'Diharapkan untuk tetap Selalu berhati hati dikarenakan batu yang licin. '
            ],
            [
                'name' => 'Bukit Lakeppo',
                'category' => 'wisata-alam',
                'description' => 'Berlokasi di dataran tinggi, Bukit Lakeppo menawarkan pengalaman sederhana tapi priceless udara segar, view alam tanpa batas, dan vibe santai jauh dari semua kebisingan. Bukit ini belum terlalu terkenal, tapi justru itu yang bikin tempat ini terasa lebih spesial dan eksklusif.',
                'address' => 'Libureng, Kec. Tanete Riaja, Barru',
                'latitude' => -4.507482378088239,
                'longitude' => 119.69803211010223,
                'main_image' => 'lakeppo.webp',
                'gallery' => [
                    'lakeppo1.webp',
                    'lakeppo2.jpg',
                    'lakeppo3.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '24 Jam',
                'activities' => [
                    'Hiking Santai',
                    'Piknik',
                    'Hunting Foto',
                    'Bersantai '
                ],
                'visitor_info' => 'Kepada pengunjung agar bisa merawat tempat ini dengan tidak merusak atau meninggalkan sampahnya. '
            ],
            [
                'name' => 'Padang Indah',
                'category' => 'wisata-alam',
                'description' => 'Padang Indah Allepperengnge. Terletak di Bacu-Bacu, Kecamatan Pujananting, Kabupaten Barru, Sulawesi Selatan, tempat ini menyuguhkan pemandangan padang rumput yang luas dan hijau, serta udara yang segar,Padang Indah Allepperengnge menawarkan keindahan alam yang khas dengan ciri khas padang rumput yang terbentang luas. Pemandangan hijau sejauh mata memandang akan membuat kamu merasa tenang dan damai. Selain itu, kamu juga bisa menikmati keindahan langit biru yang cerah dan udara pegunungan yang sejuk.',
                'address' => 'Bacu-Bacu, Kec. Pujananting, Barru',
                'latitude' => -4.579855687566581,
                'longitude' => 119.74169335243077,
                'main_image' => 'padangindah.jpg',
                'gallery' => [
                    'padangindah1.jpg',
                    'padangindah2.png',
                    'padangindah3.webp'
                ],
                'entry_fees' => null,
                'operating_hours' => '24 Jam',
                'activities' => [
                    'Hiking ',
                    'Camping',
                    'Hunting Foto',
                    'Bersantai '
                ],
                'visitor_info' => 'Kepada pengunjung agar bisa merawat tempat ini dengan tidak merusak atau meninggalkan sampahnya. '
            ],
            [
                'name' => 'Pulau Pannikiang',
                'category' => 'pantai',
                'description' => 'Pulau Pannikiang kini jadi salah satu destinasi andalan Kabupaten Barru berkat kekayaan dan keindahan alamnya. Apalagi kalau bukan karena keberadaan hutan mangrove. Dikenal orang awam dengan nama bakau. Begitu pula dengan spesies burung di sekitar Pannikiang yang cukup bervariasi, Dari 43 jenis mangrove di seluruh dunia, 17 di antaranya bisa ditemukan di Pannikiang atau terbanyak dibanding daerah lain di Sulawesi Selatan. Kawasan ini juga menjadi rumah bagi 20 spesies burung, beberapa di antaranya merupakan jenis satwa endemik. Yuk, lihat lebih dekat tentang pulau satu ini.',
                'address' => 'Madello, Kec. Balusu, Barru',
                'latitude' => -4.348423065844118,
                'longitude' => 119.5996262360158,
                'main_image' => 'pannikiang.jpg',
                'gallery' => [
                    'pannikiang1.jpg',
                    'pannikiang2.jpg',
                    'pannikiang3.jpg'
                ],
                'entry_fees' => [
                    'Hari Libur' => 20000,
                ],
                'operating_hours' => '24 Jam',
                'activities' => [
                    'Edukasi',
                    'Hunting Foto',
                    'Bersantai '
                ],
                'visitor_info' => 'Jangan lupa membawa bekal dikarenakan tidak ada penjual makanan. '
            ],
            [
                'name' => 'Air Terjun Sarang Burung',
                'category' => 'wisata-alam',
                'description' => 'Air Terjun Sarang Burung di Barru, Sulawesi Selatan, adalah sebuah keajaiban alam yang menawarkan keindahan dan kesejukan yang masih sangat alami. Dengan tujuh tingkat air terjun, tempat ini menjadi tujuan favorit bagi warga setempat saat liburan, Saat Anda berkunjung ke Sulawesi Selatan, jangan lewatkan destinasi wisata alam yang memukau ini. Terletak di Kabupaten Barru, Air Terjun Sarang Burung sudah cukup dikenal oleh warga setempat karena keunikan dan pesonanya. Mari kita jelajahi lebih lanjut tentang keindahan dan daya tarik Air Terjun Tujuh Tingkat yang mempesona ini.',
                'address' => 'Binuang, Kec. Balusu, Barru',
                'latitude' => -4.363283840819044,
                'longitude' => 119.69535525274755,
                'main_image' => 'sarbung.jpg',
                'gallery' => [
                    'sarbung1.jpg',
                    'sarbung2.jpeg',
                    'sarbung3.webp'
                ],
                'entry_fees' => null,
                'operating_hours' => '24 Jam',
                'activities' => [
                    'Bermain Air',
                    'Hunting Foto',
                    'Bersantai '
                ],
                'visitor_info' => 'Diharapkan untuk selalu berhati hati dengan batu yang licin. '
            ],
            [
                'name' => 'Hutan Pinus Lajoanging',
                'category' => 'wisata-alam',
                'description' => 'Pohon Pinus Lajoanging, Tempat Wisata di Barru yang menawarkan bentuk hutan alami. Pengunjung bisa melihat dua sisi laut Sulawesi Selatan. Namun, yang paling menarik adalah hutan pinus dan lempbah Lappa Laona yang bersih dan natural. Kalau pengunjung ingin datang pastikan untuk membawa bekal yang cukup dan kondisi badan yang fit. Tempat ini begitu tenang tetapi tak adkan menemukan jaringan telepon jika sudah berada di hutan pinus.',
                'address' => 'Harapan, Kec. Tanete Riaja, Barru',
                'latitude' => -4.5345413301707636,
                'longitude' => 119.80140532145079,
                'main_image' => 'lajoanging.jpg',
                'gallery' => [
                    'lajoanging.jpg',
                    'lajoanging2.jpeg',
                    'lajoanging1.jpg'
                ],
                'entry_fees' => null,
                'operating_hours' => '24 Jam',
                'activities' => [
                    'Hunting Foto',
                    'Bersantai '
                ],
                'visitor_info' => 'diharapkan pengunjung ingin datang pastikan untuk membawa bekal yang cukup dan kondisi badan yang fit. '
            ],
            [
                'name' => 'Lappa Laona',
                'category' => 'wisata-alam',
                'description' => 'Lappa Laona yang merupakan wisata baru yang diresmikan pada 13 Mei 2018 ini terkenal dengan bukitnya yang cantik dan hamparan rumput hijau yang luas dengan suasananya yang sejuk bak di Swiss. Lokasinya di ketinggian 1.000 meter di atas permukaan laut (mdpl) sehingga kamu bisa merasakan pemandangan cantik Sulawesi Selatan dari ketinggian. Saat malam tiba, kamu akan melihat indahnya kerlip bintang di langit dan lampu kota. Penduduk sekitar menyebut Lappa Laona dengan sebutan lapangan seluas mata memandang. ',
                'address' => 'Harapan, Kec. Tanete Riaja, Barru',
                'latitude' => -4.560785165993819,
                'longitude' => 119.75913527947819,
                'main_image' => 'laplon.jpg',
                'gallery' => [
                    'laplon1.jpg',
                    'laplon2.jpeg',
                    'laplon3.jpg'
                ],
                'entry_fees' => [
                    'Motor' => 3000,
                    'Mobil' => 5000
                ],
                'operating_hours' => '24 Jam',
                'activities' => [
                    'Hunting Foto',
                    'Camping',
                    'Menimati Sunset',
                    'Bersantai '
                ],
                'visitor_info' => 'pengunjung ingin datang pastikan untuk membawa bekal yang cukup dan kondisi badan yang fit. '
            ],
            [
                'name' => 'Baruttungge',
                'category' => 'wisata-alam',
                'description' => 'Wisata Air Terjun Baruttungnge merupakan destinasi wisata alam yang terletak di desa Lalabata kecamatan Tanete rilau Kabupaten Barru berjarak 3 km dari jalan trans sulawesi , mudah di jangkau dan memiliki akses jalan yang memadai. ',
                'address' => 'Lalabata, Kec. Tanete Rilau, Barru',
                'latitude' => -4.534013482181187,
                'longitude' => 119.62299783920474,
                'main_image' => 'baruttung.jpg',
                'gallery' => [
                    'baruttung1.jpg',
                    'baruttung2.jpeg',
                    'baruttung.jpg'
                ],
                'entry_fees' => [
                    'Motor' => 10000,
                ],
                'operating_hours' => '24 Jam',
                'activities' => [
                    'Hunting Foto',
                    'Camping',
                    'Menimati Sunset',
                    'Bersantai '
                ],
                'visitor_info' => ' '
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}