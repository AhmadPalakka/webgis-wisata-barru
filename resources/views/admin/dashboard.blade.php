@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            🎯 Dashboard Admin WebGIS Barru
        </h1>
        <p class="text-gray-600">
            Selamat datang di panel administrasi Portal WebGIS Wisata Barru
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Destinations -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Total Destinasi</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalDestinations ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Active Destinations -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Aktif</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $activeDestinations ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Inactive Destinations -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tidak Aktif</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $inactiveDestinations ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Trashed Destinations -->
        <!-- <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100 text-gray-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Terhapus</h3>
                    <p class="text-3xl font-bold text-gray-600">{{ $trashedDestinations ?? 0 }}</p>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Quick Actions Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4">🚀 Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.destinations.create') }}" 
                   class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                    <div class="p-2 bg-blue-500 rounded-lg mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">Tambah Destinasi</div>
                        <div class="text-sm text-gray-600">Buat destinasi wisata baru</div>
                    </div>
                </a>

                <a href="{{ route('admin.destinations.index') }}" 
                   class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200">
                    <div class="p-2 bg-green-500 rounded-lg mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">Kelola Destinasi</div>
                        <div class="text-sm text-gray-600">Edit dan hapus destinasi</div>
                    </div>
                </a>

                <!-- <a href="{{ route('admin.destinations.trashed') }}" 
                   class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <div class="p-2 bg-gray-500 rounded-lg mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">Trash Destinasi</div>
                        <div class="text-sm text-gray-600">Kelola destinasi terhapus</div>
                    </div>
                </a> -->

                <!-- <a href="{{ route('admin.destinations.export') }}" 
                   class="flex items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors duration-200">
                    <div class="p-2 bg-yellow-500 rounded-lg mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">Export Data</div>
                        <div class="text-sm text-gray-600">Download data CSV</div>
                    </div>
                </a> -->
            </div>
        </div>

        <!-- Recent Destinations -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📍 Destinasi Terbaru</h3>
            
            @if(isset($recentDestinations) && $recentDestinations->count() > 0)
                <div class="space-y-3">
                    @foreach($recentDestinations as $destination)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                @php $categoryInfo = $destination->getCategoryInfo(); @endphp
                                <span class="text-white text-lg">{{ $categoryInfo['icon'] }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">{{ $destination->name }}</div>
                                <div class="text-sm text-gray-600">{{ $categoryInfo['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $destination->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $destination->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $destination->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                                <a href="{{ route('admin.destinations.show', $destination) }}" 
                                   class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-4xl text-gray-300 mb-4">📍</div>
                    <div class="text-gray-500">Belum ada destinasi yang dibuat</div>
                    <a href="{{ route('admin.destinations.create') }}" 
                       class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Tambah Destinasi Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4">🔧 Informasi Sistem</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">Laravel {{ app()->version() }}</div>
                <div class="text-sm text-gray-600">Framework Version</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">PHP {{ PHP_VERSION }}</div>
                <div class="text-sm text-gray-600">PHP Version</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">WebGIS Barru</div>
                <div class="text-sm text-gray-600">Tourism Portal</div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh dashboard stats every 30 seconds
setInterval(function() {
    // You can add AJAX call here to refresh statistics without page reload
    console.log('Dashboard stats can be refreshed here');
}, 30000);

// Welcome message
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        setTimeout(function() {
            // Auto-hide success message after 5 seconds
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }
        }, 5000);
    @endif
});
</script>
@endsection