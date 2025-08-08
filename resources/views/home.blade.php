@extends('layouts.app')

@section('title', 'Portal WebGIS Wisata Barru')

@section('content')
<!-- Hero Section dengan Location Call-to-Action -->
<div class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background dengan parallax effect -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-500 to-purple-600"></div>
    <div class="absolute inset-0 bg-black/20"></div>
    
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-300/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
            Jelajahi Keindahan Wisata
            <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                BARRU
            </span>
        </h1>
        
        <p class="text-xl md:text-2xl mb-8 text-blue-100 leading-relaxed">
            Temukan destinasi wisata menakjubkan di Barru dengan teknologi WebGIS dan GPS terdepan
        </p>
        
        <!-- Location-based CTA -->
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-8 border border-white/20">
            <div class="flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-yellow-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-lg font-semibold">Aktifkan lokasi untuk pengalaman terbaik</span>
            </div>
            <p class="text-blue-100 text-sm">
                Jelajahi Pesona Keindahan Wisata Yang Ada Di kabupten Barru Dengan Mudah 
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="space-y-4 md:space-y-0 md:space-x-6 md:flex md:justify-center">
            <a href="{{ route('map.index') }}" class="btn-primary inline-block text-lg px-8 py-4 group">
                <span class="flex items-center">
                    🗺️ Jelajahi Peta Interaktif
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </a>
            <a href="{{ route('destinations.index') }}" class="btn-secondary inline-block text-lg px-8 py-4">
                📍 Lihat Semua Destinasi
            </a>
        </div>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-3 gap-4 mt-12 text-center">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                <div class="text-2xl font-bold">{{ \App\Models\Destination::count() }}+</div>
                <div class="text-sm text-blue-200">Destinasi Wisata</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                <div class="text-2xl font-bold">GPS</div>
                <div class="text-sm text-blue-200">Navigation Ready</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                <div class="text-2xl font-bold">24/7</div>
                <div class="text-sm text-blue-200">Akses Online</div>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</div>

<!-- Features Section dengan Location Focus -->
<div class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Fitur Canggih WebGIS Barru
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Platform wisata digital dengan teknologi GPS dan peta interaktif untuk pengalaman eksplorasi yang tak terlupakan
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- GPS Location -->
            <div class="feature-card group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">🧭</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">GPS & Location Services</h3>
                <p class="text-gray-600 leading-relaxed">
                    Temukan lokasi Anda secara real-time dan dapatkan rekomendasi destinasi terdekat dengan akurasi tinggi menggunakan teknologi GPS terkini.
                </p>
                <div class="mt-4 flex items-center text-blue-600 text-sm font-medium">
                    <span>Akurasi hingga ±5 meter</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Interactive Map -->
            <div class="feature-card group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">🗺️</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Peta Interaktif</h3>
                <p class="text-gray-600 leading-relaxed">
                    Jelajahi peta Barru yang responsif dengan fitur zoom, pan, dan marker interaktif untuk pengalaman navigasi yang optimal.
                </p>
                <div class="mt-4 flex items-center text-blue-600 text-sm font-medium">
                    <span>Multi-layer & Real-time</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Smart Search -->
            <div class="feature-card group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">🔍</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Smart Search & Filter</h3>
                <p class="text-gray-600 leading-relaxed">
                    Cari destinasi berdasarkan nama, kategori, atau lokasi dengan sistem pencarian cerdas dan filter kategori yang komprehensif.
                </p>
                <div class="mt-4 flex items-center text-blue-600 text-sm font-medium">
                    <span>Real-time search results</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="feature-card group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">🚗</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Turn-by-Turn Navigation</h3>
                <p class="text-gray-600 leading-relaxed">
                    Dapatkan petunjuk arah detail dengan estimasi waktu tempuh dan jarak dari lokasi Anda ke destinasi pilihan.
                </p>
                <div class="mt-4 flex items-center text-blue-600 text-sm font-medium">
                    <span>Integrasi Google Maps</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Distance Calculation -->
            <div class="feature-card group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">📏</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Distance Calculation</h3>
                <p class="text-gray-600 leading-relaxed">
                    Hitung jarak real-time dari lokasi Anda ke setiap destinasi wisata menggunakan algoritma Haversine yang akurat.
                </p>
                <div class="mt-4 flex items-center text-blue-600 text-sm font-medium">
                    <span>Kalkulasi real-time</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Mobile Support -->
            <div class="feature-card group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">📱</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Mobile Responsive</h3>
                <p class="text-gray-600 leading-relaxed">
                    Akses platform dari smartphone, tablet, atau desktop dengan tampilan yang optimal dan fitur touch-friendly.
                </p>
                <div class="mt-4 flex items-center text-blue-600 text-sm font-medium">
                    <span>Cross-platform support</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Location Permission Request Section -->
<div class="py-16 bg-gradient-to-r from-green-500 to-blue-500">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-3xl mx-auto text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                🌍 Aktifkan Lokasi untuk Pengalaman Optimal
            </h2>
            <p class="text-lg mb-8 text-green-100">
                Izinkan akses lokasi untuk mendapatkan rekomendasi destinasi terdekat, 
                perhitungan jarak real-time, dan navigasi yang akurat.
            </p>
            
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl mb-3">📍</div>
                        <h4 class="font-semibold mb-2">Destinasi Terdekat</h4>
                        <p class="text-sm text-green-100">Temukan wisata dalam radius 50km dari lokasi Anda</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl mb-3">📏</div>
                        <h4 class="font-semibold mb-2">Jarak Real-time</h4>
                        <p class="text-sm text-green-100">Lihat jarak akurat ke setiap destinasi</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl mb-3">🧭</div>
                        <h4 class="font-semibold mb-2">Navigasi GPS</h4>
                        <p class="text-sm text-green-100">Petunjuk arah turn-by-turn yang akurat</p>
                    </div>
                </div>
            </div>
            
            <button onclick="requestLocationPermission()" class="btn-secondary text-lg px-8 py-4 bg-white text-green-600 hover:bg-green-50">
                🌍 Aktifkan Lokasi Sekarang
            </button>
            
            <p class="text-sm text-green-200 mt-4">
                * Lokasi Anda akan tetap privat dan hanya digunakan untuk meningkatkan pengalaman wisata
            </p>
        </div>
    </div>
</div>

<!-- Categories Preview -->
<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Kategori Destinasi Wisata
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Temukan beragam destinasi wisata Barru sesuai minat dan preferensi Anda
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @php
                $categories = \App\Models\Destination::getCategories();
            @endphp
            
            @foreach($categories as $key => $category)
            <a href="{{ route('map.index') }}?category={{ $key }}" class="category-preview-card group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">{{ $category['icon'] }}</div>
                <h3 class="font-bold text-gray-800 mb-2">{{ $category['name'] }}</h3>
                <div class="text-sm text-gray-600 mb-3">
                    {{ \App\Models\Destination::where('category', $key)->where('is_active', true)->count() }} lokasi
                </div>
                <div class="text-blue-600 text-sm font-medium group-hover:translate-x-1 transition-transform">
                    Jelajahi →
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-3xl mx-auto text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                🚀 Mulai Petualangan Anda Hari Ini
            </h2>
            <p class="text-lg mb-8 text-blue-100">
                Jelajahi keindahan Barru dengan teknologi WebGIS terdepan. 
                Temukan destinasi impian, dapatkan petunjuk arah , dan ciptakan kenangan tak terlupakan.
            </p>
            
            <div class="space-y-4 md:space-y-0 md:space-x-6 md:flex md:justify-center">
                <a href="{{ route('map.index') }}" class="btn-primary inline-block text-lg px-8 py-4">
                    🗺️ Buka Peta Interaktif
                </a>
                <a href="{{ route('destinations.index') }}" class="btn-secondary inline-block text-lg px-8 py-4 bg-white text-blue-600 hover:bg-blue-50">
                    📱 Lihat di Mobile
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function requestLocationPermission() {
    if (!navigator.geolocation) {
        alert('⚠️ Geolocation tidak didukung oleh browser Anda');
        return;
    }
    
    // Show immediate feedback
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = '🔄 Meminta izin lokasi...';
    button.disabled = true;
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const accuracy = Math.round(position.coords.accuracy);
            
            // Restore button
            button.textContent = originalText;
            button.disabled = false;
            
            // Show success message
            alert(`✅ Lokasi berhasil diaktifkan!\n\nAkurasi: ±${accuracy}m\n\nSekarang Anda dapat menggunakan semua fitur GPS di peta interaktif.`);
            
            // Redirect to map with location enabled
            setTimeout(() => {
                window.location.href = '{{ route("map.index") }}?enable_location=true';
            }, 1000);
        },
        function(error) {
            // Restore button
            button.textContent = originalText;
            button.disabled = false;
            
            let message = '❌ Gagal mengakses lokasi:\n\n';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message += '• Akses lokasi ditolak\n• Silakan aktifkan GPS dan izinkan akses lokasi di browser\n• Coba refresh halaman dan klik tombol lagi';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message += '• Informasi lokasi tidak tersedia\n• Pastikan GPS device aktif';
                    break;
                case error.TIMEOUT:
                    message += '• Permintaan lokasi timeout\n• Coba lagi dalam beberapa saat';
                    break;
                default:
                    message += '• Kesalahan tidak diketahui\n• Silakan coba lagi';
                    break;
            }
            
            alert(message);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        }
    );
}
</script>
@endsection