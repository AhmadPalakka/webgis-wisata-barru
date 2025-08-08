@extends('admin.layouts.app')

@section('title', $destination->name . ' - Detail Destinasi')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                    🏠 Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('admin.destinations.index') }}" class="text-gray-600 hover:text-gray-900">
                        Destinasi
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-500">{{ Str::limit($destination->name, 30) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $destination->name }}</h1>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    @php $categoryInfo = $destination->getCategoryInfo(); @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                          style="background-color: {{ $categoryInfo['color'] === 'green' ? '#dcfce7' : ($categoryInfo['color'] === 'blue' ? '#dbeafe' : ($categoryInfo['color'] === 'yellow' ? '#fef3c7' : ($categoryInfo['color'] === 'purple' ? '#f3e8ff' : '#e0e7ff'))) }}; 
                                 color: {{ $categoryInfo['color'] === 'green' ? '#166534' : ($categoryInfo['color'] === 'blue' ? '#1d4ed8' : ($categoryInfo['color'] === 'yellow' ? '#92400e' : ($categoryInfo['color'] === 'purple' ? '#7c3aed' : '#4338ca'))) }};">
                        {{ $categoryInfo['icon'] }} {{ $categoryInfo['name'] }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $destination->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $destination->is_active ? '✅ Aktif' : '❌ Non-Aktif' }}
                    </span>
                    <span>📅 Dibuat {{ $destination->created_at->format('d M Y') }}</span>
                    <span>🔄 Diperbarui {{ $destination->updated_at->format('d M Y') }}</span>
                </div>
            </div>
            
            <div class="flex space-x-3">
    <a href="{{ route('destinations.show', $destination->slug) }}" target="_blank" 
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
        🌐 Lihat di Website
    </a>
    <a href="{{ route('admin.destinations.edit', $destination) }}" 
       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
        ✏️ Edit Destinasi
    </a>
    <form action="{{ route('admin.destinations.toggle-status', $destination) }}" method="POST" class="inline">
        @csrf
        @method('PATCH')
        <button type="submit" 
                class="px-4 py-2 rounded-lg transition-colors duration-200 {{ $destination->is_active ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}"
                onclick="return confirm('Yakin ingin mengubah status destinasi ini?')">
            {{ $destination->is_active ? '❌ Nonaktifkan' : '✅ Aktifkan' }}
        </button>
    </form>
</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Image and Gallery -->
        <div class="lg:col-span-2">
            <!-- Main Image -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">📷 Gambar Utama</h2>
                    <div class="relative">
                        <img src="{{ asset('images/destinations/' . $destination->main_image) }}" 
                             alt="{{ $destination->name }}"
                             class="w-full h-96 object-cover rounded-lg shadow-lg"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                            {{ $destination->main_image }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">📝 Deskripsi</h2>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($destination->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Activities -->
            @if($destination->activities && count($destination->activities) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">🎯 Aktivitas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($destination->activities as $activity)
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm">🎯</span>
                            </div>
                            <span class="text-gray-700 font-medium">{{ $activity }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Gallery Section -->
            @if($destination->gallery && count($destination->gallery) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">🖼️ Galeri Foto</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($destination->gallery as $index => $image)
                        <div class="relative group">
                            <img src="{{ asset('images/destinations/' . $image) }}" 
                                 alt="{{ $destination->name }} - Foto {{ $index + 1 }}"
                                 class="w-full h-32 object-cover rounded-lg shadow-md group-hover:shadow-lg transition-shadow"
                                 onerror="this.style.display='none'">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                                <span class="text-white opacity-0 group-hover:opacity-100 text-sm font-medium">{{ $image }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Details and Info -->
        <div class="lg:col-span-1">
            <!-- Quick Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">ℹ️ Informasi Cepat</h2>
                    
                    <!-- Location -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">📍 Koordinat</h3>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="text-sm">
                                <div class="font-mono">Lat: {{ number_format($destination->latitude, 6) }}</div>
                                <div class="font-mono">Lng: {{ number_format($destination->longitude, 6) }}</div>
                            </div>
                            <a href="https://www.google.com/maps?q={{ $destination->latitude }},{{ $destination->longitude }}" 
                               target="_blank" 
                               class="mt-2 inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                📍 Buka Google Maps
                            </a>
                        </div>
                    </div>

                    <!-- Address -->
                    @if($destination->address)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">📮 Alamat</h3>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $destination->address }}</p>
                    </div>
                    @endif

                    <!-- Contact -->
                    @if($destination->contact)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">📞 Kontak</h3>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $destination->contact }}</p>
                    </div>
                    @endif

                    <!-- Operating Hours -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">🕒 Jam Operasional</h3>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $destination->operating_hours ?? '24 jam' }}</p>
                    </div>

                    <!-- Entry Fees -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">💰 Tarif Masuk</h3>
                        @if($destination->entry_fees)
                            <div class="bg-gray-50 p-3 rounded-lg space-y-1">
                                @foreach($destination->entry_fees as $vehicle => $fee)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 capitalize">{{ $vehicle }}:</span>
                                    <span class="font-medium text-gray-900">Rp {{ number_format($fee, 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-green-600 font-medium bg-green-50 p-3 rounded-lg">🆓 Gratis</p>
                        @endif
                    </div>

                    <!-- Visitor Info -->
                    @if($destination->visitor_info)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">👥 Info Pengunjung</h3>
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <p class="text-sm text-gray-700">{{ $destination->visitor_info }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">📊 Statistik</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Kategori yang sama:</span>
                            <span class="font-semibold text-blue-600">{{ $stats['category_count'] }} destinasi</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Destinasi sekitar:</span>
                            <span class="font-semibold text-green-600">{{ $stats['same_area_count'] }} lokasi</span>
                        </div>
                        
                        <div class="border-t pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Dibuat:</span>
                                <span class="text-sm font-medium">{{ $destination->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm text-gray-600">Diperbarui:</span>
                                <span class="text-sm font-medium">{{ $destination->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Destinations -->
            @if($relatedDestinations->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">🔗 Destinasi Sejenis</h2>
                    <div class="space-y-3">
                        @foreach($relatedDestinations as $related)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <img src="{{ asset('images/destinations/' . $related->main_image) }}" 
                                 alt="{{ $related->name }}"
                                 class="w-12 h-12 object-cover rounded-lg mr-3"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $related->name }}</h4>
                                <p class="text-xs text-gray-600">{{ Str::limit($related->description, 50) }}</p>
                            </div>
                            <a href="{{ route('admin.destinations.show', $related) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Lihat →
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Bar -->
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h3 class="text-lg font-medium text-gray-900">Kelola Destinasi</h3>
                <p class="text-sm text-gray-600">Pilih aksi yang ingin dilakukan pada destinasi ini</p>
            </div>
            <div class="flex space-x-3">
    <a href="{{ route('admin.destinations.index') }}" 
       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
        ← Kembali ke Daftar
    </a>
    <a href="{{ route('admin.destinations.edit', $destination) }}" 
       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
        ✏️ Edit Destinasi
    </a>
    <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="inline" id="delete-form">
        @csrf
        @method('DELETE')
        <button type="button" onclick="confirmDelete('{{ $destination->name }}')" 
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
            🗑️ Hapus Destinasi
        </button>
    </form>
</div>
        </div>
    </div>
</div>

<script>
function confirmDelete(destinationName) {
    if (confirm(`Apakah Anda yakin ingin menghapus destinasi "${destinationName}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection