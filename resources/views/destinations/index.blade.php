@extends('layouts.app')

@section('title', 'Destinasi Wisata Barru')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-500 pt-24 pb-16">
    <div class="container mx-auto px-4">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Destinasi Wisata Barru</h1>
            <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">
                Jelajahi keindahan Wisata alam dan budaya Kabupaten Barru melalui berbagai destinasi wisata menarik
            </p>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="bg-white shadow-sm py-8">
    <div class="container mx-auto px-4">
        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto mb-8">
            <form method="GET" action="{{ route('destinations.index') }}">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="🔍 Cari destinasi wisata..." 
                        class="w-full px-6 py-4 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg shadow-sm"
                    >
                    <button type="submit" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Keep category filter if exists -->
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </form>
        </div>
        
        <!-- Category Filter -->
        <div class="flex flex-wrap justify-center gap-4 mb-8">
            <a href="{{ route('destinations.index') }}" 
               class="px-6 py-3 rounded-full text-sm font-medium transition-all duration-200 {{ !request('category') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                🌟 Semua Kategori
            </a>
            
            @foreach($categories as $key => $category)
            <a href="{{ route('destinations.index', ['category' => $key] + request()->all()) }}" 
               class="px-6 py-3 rounded-full text-sm font-medium transition-all duration-200 {{ request('category') == $key ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $category['icon'] }} {{ $category['name'] }}
            </a>
            @endforeach
        </div>
        
        <!-- Results Info -->
        <div class="text-center">
            <p class="text-gray-600">
                @if(request('search') || request('category'))
                    Menampilkan {{ $destinations->total() }} destinasi
                    @if(request('search'))
                        untuk pencarian "<strong>{{ request('search') }}</strong>"
                    @endif
                    @if(request('category'))
                        dalam kategori "<strong>{{ $categories[request('category')]['name'] }}</strong>"
                    @endif
                    
                    <a href="{{ route('destinations.index') }}" class="text-blue-500 hover:underline ml-2">Reset Filter</a>
                @else
                    Menampilkan {{ $destinations->total() }} destinasi wisata
                @endif
            </p>
        </div>
    </div>
</div>

<!-- Destinations Grid -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($destinations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($destinations as $destination)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 group">
                        <!-- Image -->
                        <div class="relative h-56 overflow-hidden bg-gray-200">
                            <img 
                                src="{{ asset('images/destinations/' . $destination->main_image) }}" 
                                alt="{{ $destination->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDQwMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI0MDAiIGhlaWdodD0iMzAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xNzUgMTIwSDIyNVYxODBIMTc1VjEyMFoiIGZpbGw9IiM5Q0EzQUYiLz4KPHBhdGggZD0iTTE5MiAxMzVMMjA4IDEzNUwyMDAgMTQ1TDE5MiAxMzVaIiBmaWxsPSIjOUNBM0FGIi8+CjwvZz4KPC9zdmc+'"
                            >
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $destination->getCategoryInfo()['icon'] }} {{ $destination->getCategoryInfo()['name'] }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                                {{ $destination->name }}
                            </h3>
                            
                            @if($destination->address)
                            <p class="text-sm text-gray-500 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ Str::limit($destination->address, 40) }}
                            </p>
                            @endif
                            
                            <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                                {{ Str::limit($destination->description, 150) }}
                            </p>
                            
                            <!-- Features -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                @if($destination->entry_fees)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                        💰 Berbayar
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                        🆓 Gratis
                                    </span>
                                @endif
                                
                                @if($destination->activities && count($destination->activities) > 0)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">
                                        🎯 {{ count($destination->activities) }} Aktivitas
                                    </span>
                                @endif
                                
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">
                                    ⏰ {{ $destination->operating_hours }}
                                </span>
                            </div>
                            
                            <!-- Action Button -->
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    📍 {{ number_format($destination->latitude, 4) }}, {{ number_format($destination->longitude, 4) }}
                                </div>
                                
                                <a href="{{ route('destinations.show', $destination) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors group-hover:bg-blue-600">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($destinations->hasPages())
                <div class="mt-12">
                    <div class="flex justify-center">
                        {{ $destinations->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="text-8xl text-gray-300 mb-6">🏝️</div>
                <h3 class="text-2xl font-bold text-gray-600 mb-4">Tidak ada destinasi ditemukan</h3>
                <p class="text-gray-500 mb-8 text-lg">
                    @if(request('search'))
                        Coba ubah kata kunci pencarian Anda atau hapus filter yang diterapkan
                    @else
                        Destinasi wisata belum tersedia saat ini
                    @endif
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('destinations.index') }}" class="btn-primary">
                        🔄 Lihat Semua Destinasi
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Call to Action -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">
            Jelajahi Barru Lebih Dalam
        </h2>
        <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">
            Temukan lokasi setiap destinasi wisata dengan mudah melalui peta interaktif kami
        </p>
        <a href="#" class="btn-secondary text-lg bg-white text-blue-600 hover:bg-blue-50">
            🗺️ Buka Peta Interaktif
        </a>
    </div>
</div>
@endsection