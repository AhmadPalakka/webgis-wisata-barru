@extends('layouts.app')

@section('title', $destination->name . ' - Destinasi Wisata Barru')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 pt-24 pb-6">
    <div class="container mx-auto px-4">
        <nav class="text-sm">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="{{ route('home') }}" class="text-blue-500 hover:text-blue-700 transition-colors">
                        Beranda
                    </a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('destinations.index') }}" class="text-blue-500 hover:text-blue-700 transition-colors">
                        Destinasi
                    </a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-600">{{ $destination->name }}</span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Hero Section - Show image or gradient header -->
@php
    $imagePath = public_path('images/destinations/' . $destination->main_image);
    $hasMainImage = file_exists($imagePath);
@endphp

@if($hasMainImage)
    <!-- Hero Image Section -->
    <div class="relative h-96 md:h-[500px] overflow-hidden bg-gray-200">
        <img 
            src="{{ asset('images/destinations/' . $destination->main_image) }}" 
            alt="{{ $destination->name }}"
            class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-black/30"></div>
        
        <!-- Title Overlay -->
        <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-black/70 to-transparent">
            <div class="container mx-auto">
                <div class="flex items-center gap-3 mb-3">
                    <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $destination->getCategoryInfo()['icon'] }} {{ $destination->getCategoryInfo()['name'] }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-2">{{ $destination->name }}</h1>
                @if($destination->address)
                <div class="flex items-center text-white/90 text-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $destination->address }}
                </div>
                @endif
            </div>
        </div>
    </div>
@else
    <!-- Alternative Header without Image -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 pt-6 pb-16">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold">
                        {{ $destination->getCategoryInfo()['icon'] }} {{ $destination->getCategoryInfo()['name'] }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl font-bold mb-4">{{ $destination->name }}</h1>
                @if($destination->address)
                <div class="flex items-center justify-center text-white/90 text-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $destination->address }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Main Content -->
<div class="py-12 bg-white">
    <div class="container mx-auto px-4">
        @php
            // Check if we have gallery images
            $galleryImages = [];
            if($destination->gallery && count($destination->gallery) > 0) {
                foreach($destination->gallery as $image) {
                    $imagePath = public_path('images/destinations/' . $image);
                    if(file_exists($imagePath)) {
                        $galleryImages[] = $image;
                    }
                }
            }
            
            // Determine layout: use full width if no main image and no gallery
            $useFullWidth = !$hasMainImage && count($galleryImages) === 0;
        @endphp
        
        <div class="grid grid-cols-1 {{ $useFullWidth ? 'max-w-4xl mx-auto' : 'lg:grid-cols-3' }} gap-12">
            <!-- Left Column - Main Content -->
            <div class="{{ $useFullWidth ? '' : 'lg:col-span-2' }}">
                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Tentang Destinasi</h2>
                    <div class="prose prose-lg text-gray-600 leading-relaxed">
                        <p>{{ $destination->description }}</p>
                    </div>
                </div>

                <!-- Activities -->
                @if($destination->activities && count($destination->activities) > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Aktivitas yang Bisa Dilakukan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($destination->activities as $activity)
                        <div class="flex items-center p-4 bg-blue-50 rounded-lg border border-blue-100 hover:bg-blue-100 transition-colors">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <span class="text-white text-lg">🎯</span>
                            </div>
                            <span class="text-gray-700 font-medium">{{ $activity }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Gallery Section - Show only if images exist -->
                @if(count($galleryImages) > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Galeri Foto</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($galleryImages as $index => $image)
                        <div class="relative group cursor-pointer gallery-item" onclick="openLightbox({{ $index }})">
                            <img 
                                src="{{ asset('images/destinations/' . $image) }}" 
                                alt="{{ $destination->name }} - Foto {{ $index + 1 }}"
                                class="w-full h-40 object-cover rounded-lg shadow-md group-hover:shadow-xl transition-all duration-300"
                            >
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 rounded-lg flex items-center justify-center">
                                <div class="bg-white/90 backdrop-blur-sm rounded-full p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <!-- Alternative: Show location info when no gallery -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Lokasi Destinasi</h2>
                    <div class="bg-blue-50 rounded-lg p-8 border border-blue-100">
                        <div class="text-center">
                            <div class="text-6xl mb-4">📍</div>
                            <h3 class="text-xl font-bold text-blue-800 mb-3">{{ $destination->name }}</h3>
                            @if($destination->address)
                            <p class="text-blue-600 mb-4 text-lg">{{ $destination->address }}</p>
                            @endif
                            <div class="bg-white rounded-lg p-4 inline-block">
                                <p class="text-sm text-gray-600 mb-1">Koordinat GPS:</p>
                                <p class="font-mono text-blue-700 font-semibold">
                                    {{ number_format($destination->latitude, 6) }}, {{ number_format($destination->longitude, 6) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Visitor Info -->
                @if($destination->visitor_info)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Pengunjung</h2>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <span class="text-white text-lg">ℹ️</span>
                            </div>
                            <p class="text-gray-700 leading-relaxed">{{ $destination->visitor_info }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column - Sidebar (Only show if not full width) -->
            @if(!$useFullWidth)
            <div class="lg:col-span-1">
                <!-- Quick Info Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-100 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Cepat</h3>
                    
                    <!-- Operating Hours -->
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">Jam Operasional</span>
                        </div>
                        <p class="text-gray-600 ml-8">{{ $destination->operating_hours }}</p>
                    </div>

                    <!-- Entry Fees -->
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">Tarif Masuk</span>
                        </div>
                        @if($destination->entry_fees)
                            <div class="ml-8 space-y-1">
                                @foreach($destination->entry_fees as $vehicle => $fee)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 capitalize">{{ $vehicle }}:</span>
                                    <span class="font-medium text-gray-800">Rp {{ number_format($fee, 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-green-600 font-medium ml-8 flex items-center">
                                <span class="text-lg mr-2">🆓</span> Gratis
                            </p>
                        @endif
                    </div>

                    <!-- Contact -->
                    @if($destination->contact)
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">Kontak</span>
                        </div>
                        <p class="text-gray-600 ml-8">{{ $destination->contact }}</p>
                    </div>
                    @endif

                    <!-- Coordinates -->
                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">Koordinat</span>
                        </div>
                        <div class="ml-8 text-sm text-gray-600">
                            <p>Lat: {{ number_format($destination->latitude, 6) }}</p>
                            <p>Lng: {{ number_format($destination->longitude, 6) }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button onclick="openMapModal()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                            🗺️ Lihat di Peta
                        </button>
                        
                        <a href="https://www.google.com/maps?q={{ $destination->latitude }},{{ $destination->longitude }}" 
                           target="_blank" 
                           class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 text-center block">
                            📍 Buka Google Maps
                        </a>
                        
                        <button onclick="shareDestination()" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                            📤 Bagikan
                        </button>
                    </div>
                </div>

                <!-- Navigation Card -->
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-bold text-blue-800 mb-4">Jelajahi Destinasi Lain</h3>
                    <div class="space-y-3">
                        <a href="{{ route('destinations.index') }}" class="block text-blue-600 hover:text-blue-800 font-medium transition-colors">
                            ← Kembali ke Daftar Destinasi
                        </a>
                        <a href="#" class="block text-blue-600 hover:text-blue-800 font-medium transition-colors">
                            🗺️ Lihat Peta Semua Destinasi
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Quick Info for Full Width Layout -->
        @if($useFullWidth)
        <div class="max-w-2xl mx-auto mt-12">
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">Informasi Cepat</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Operating Hours -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700 mb-2">Jam Operasional</h4>
                        <p class="text-gray-600">{{ $destination->operating_hours }}</p>
                    </div>

                    <!-- Entry Fees -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700 mb-2">Tarif Masuk</h4>
                        @if($destination->entry_fees)
                            <div class="space-y-1">
                                @foreach($destination->entry_fees as $vehicle => $fee)
                                <div class="text-sm text-gray-600">
                                    {{ ucfirst($vehicle) }}: Rp {{ number_format($fee, 0, ',', '.') }}
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-green-600 font-medium">🆓 Gratis</p>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mt-8">
                    <button onclick="openMapModal()" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                        🗺️ Lihat di Peta
                    </button>
                    
                    <a href="https://www.google.com/maps?q={{ $destination->latitude }},{{ $destination->longitude }}" 
                       target="_blank" 
                       class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 text-center">
                        📍 Google Maps
                    </a>
                    
                    <button onclick="shareDestination()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                        📤 Bagikan
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal for Gallery -->
@if(count($galleryImages) > 0)
<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeLightbox()" class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300 transition-colors">
            ✕ Tutup
        </button>
        
        <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        
        <div class="absolute top-1/2 left-4 transform -translate-y-1/2">
            <button onclick="prevImage()" class="bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
        </div>
        
        <div class="absolute top-1/2 right-4 transform -translate-y-1/2">
            <button onclick="nextImage()" class="bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
        
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-center">
            <span id="image-counter">1 / {{ count($galleryImages) }}</span>
        </div>
    </div>
</div>
@endif

<!-- Map Modal (Placeholder) -->
<div id="mapModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-4xl w-full max-h-[80vh] overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold">Lokasi {{ $destination->name }}</h3>
            <button onclick="closeMapModal()" class="text-gray-500 hover:text-gray-700 text-xl">✕</button>
        </div>
        <div class="h-96 bg-gray-200 flex items-center justify-center">
            <div class="text-center text-gray-500">
                <div class="text-4xl mb-2">🗺️</div>
                <p>Peta interaktif akan ditambahkan di tahap selanjutnya</p>
                <p class="text-sm mt-2">Koordinat: {{ $destination->latitude }}, {{ $destination->longitude }}</p>
            </div>
        </div>
    </div>
</div>

<script>
// Gallery Lightbox
const gallery = @json($galleryImages ?? []);
let currentImageIndex = 0;

function openLightbox(index) {
    currentImageIndex = index;
    const lightbox = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-img');
    const counter = document.getElementById('image-counter');
    
    img.src = '/images/destinations/' + gallery[index];
    counter.textContent = (index + 1) + ' / ' + gallery.length;
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.getElementById('lightbox').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function prevImage() {
    currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : gallery.length - 1;
    openLightbox(currentImageIndex);
}

function nextImage() {
    currentImageIndex = currentImageIndex < gallery.length - 1 ? currentImageIndex + 1 : 0;
    openLightbox(currentImageIndex);
}

// Map Modal
function openMapModal() {
    document.getElementById('mapModal').classList.remove('hidden');
    document.getElementById('mapModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeMapModal() {
    document.getElementById('mapModal').classList.add('hidden');
    document.getElementById('mapModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Share Function
function shareDestination() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $destination->name }} - Destinasi Wisata Barru',
            text: '{{ Str::limit($destination->description, 100) }}',
            url: window.location.href
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link destinasi telah disalin ke clipboard!');
        });
    }
}

// Keyboard navigation for lightbox
document.addEventListener('keydown', function(e) {
    const lightbox = document.getElementById('lightbox');
    if (!lightbox.classList.contains('hidden')) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'ArrowRight') nextImage();
    }
    
    // Close modals with Escape
    if (e.key === 'Escape') {
        closeMapModal();
    }
});

// Close modals when clicking outside
document.getElementById('lightbox')?.addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});

document.getElementById('mapModal').addEventListener('click', function(e) {
    if (e.target === this) closeMapModal();
});
</script>
@endsection