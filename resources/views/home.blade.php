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