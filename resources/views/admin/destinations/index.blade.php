@extends('admin.layouts.app')

@section('title', 'Kelola Destinasi Wisata')

@section('content')
<!-- Tambahkan CSRF TOKEN di head jika belum ada -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Kelola Destinasi Wisata</h1>
            <p class="text-gray-600">Tambah, edit, dan kelola destinasi wisata Barru</p>
        </div>
        
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            <!-- Trash Button -->
            <!-- <a href="{{ route('admin.destinations.trashed') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                🗑️ Trash (<span id="trash-count">{{ \App\Models\Destination::onlyTrashed()->count() }}</span>)
            </a> -->
            
            <!-- Bulk Actions (Initially Hidden) -->
            <div id="bulk-actions" class="hidden flex gap-2">
                <button onclick="bulkDelete()" 
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                    🗑️ Hapus Terpilih
                </button>
                <button onclick="clearSelection()" 
                        class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ❌ Batal
                </button>
            </div>
            
            <!-- Export & Create -->
            <!-- <a href="{{ route('admin.destinations.export') }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                📊 Export CSV
            </a> -->
            
            <a href="{{ route('admin.destinations.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                ➕ Tambah Destinasi
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
            <span>{{ session('success') }}</span>
            @if(session('deleted_destination'))
                <button onclick="undoDelete()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm ml-4">
                    ↩️ Undo
                </button>
            @endif
            <button onclick="closeAlert('success-alert')" class="text-green-700 hover:text-green-900">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div id="error-alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="closeAlert('error-alert')" class="text-red-700 hover:text-red-900">✕</button>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.destinations.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-center md:space-x-4">
            <!-- Search -->
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="🔍 Cari destinasi (nama, deskripsi, alamat)..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Category Filter -->
            <div>
                <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach(\App\Models\Destination::getCategories() as $key => $category)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                            {{ $category['icon'] }} {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>❌ Tidak Aktif</option>
                </select>
            </div>
            
            <!-- Sort -->
            <div>
                <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>📝 Nama A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>📝 Nama Z-A</option>
                    <option value="created_desc" {{ request('sort', 'created_desc') == 'created_desc' ? 'selected' : '' }}>🆕 Terbaru</option>
                    <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>📅 Terlama</option>
                    <option value="category" {{ request('sort') == 'category' ? 'selected' : '' }}>🏷️ Kategori</option>
                </select>
            </div>
            
            <!-- Search Button -->
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    🔍 Filter
                </button>
                @if(request()->hasAny(['search', 'category', 'status', 'sort']))
                    <a href="{{ route('admin.destinations.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        ↻ Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <span class="text-2xl">📍</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $destinations->total() }}</div>
                    <div class="text-gray-600 text-sm">Total Destinasi</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <span class="text-2xl">✅</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\Destination::where('is_active', true)->count() }}</div>
                    <div class="text-gray-600 text-sm">Aktif</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-full mr-3">
                    <span class="text-2xl">❌</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-600">{{ \App\Models\Destination::where('is_active', false)->count() }}</div>
                    <div class="text-gray-600 text-sm">Tidak Aktif</div>
                </div>
            </div>
        </div>
        
        <!-- <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="bg-gray-100 p-3 rounded-full mr-3">
                    <span class="text-2xl">🗑️</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-600">{{ \App\Models\Destination::onlyTrashed()->count() }}</div>
                    <div class="text-gray-600 text-sm">Terhapus</div>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Destinations Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- Bulk Select Checkbox -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <label class="flex items-center">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2">Pilih</span>
                            </label>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Destinasi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lokasi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dibuat
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($destinations as $destination)
                        <tr class="hover:bg-gray-50 destination-row" data-destination-id="{{ $destination->id }}">
                            <!-- Bulk Select Checkbox -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="destination-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                       value="{{ $destination->id }}">
                            </td>
                            
                            <!-- Destination Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <img class="h-16 w-16 rounded-lg object-cover" 
                                             src="{{ $destination->main_image_url }}" 
                                             alt="{{ $destination->name }}"
                                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $destination->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($destination->description, 60) }}</div>
                                        @if($destination->address)
                                            <div class="text-xs text-gray-400 mt-1">📍 {{ Str::limit($destination->address, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php $categoryInfo = $destination->getCategoryInfo(); @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $categoryInfo['color'] }}-100 text-{{ $categoryInfo['color'] }}-800">
                                    {{ $categoryInfo['icon'] }} {{ $categoryInfo['name'] }}
                                </span>
                            </td>

                            <!-- Location -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ number_format($destination->latitude, 4) }}, {{ number_format($destination->longitude, 4) }}</div>
                                @if($destination->gallery && is_array($destination->gallery))
                                    <div class="text-xs text-gray-400">📸 {{ count($destination->gallery) }} foto</div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <!-- Status Toggle -->
                                    <button onclick="toggleStatus({{ $destination->id }}, {{ $destination->is_active ? 'false' : 'true' }})"
                                            class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $destination->is_active ? 'bg-blue-600' : 'bg-gray-200' }}"
                                            role="switch" aria-checked="{{ $destination->is_active ? 'true' : 'false' }}">
                                        <span class="pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $destination->is_active ? 'translate-x-5' : 'translate-x-0' }}">
                                            <span class="opacity-100 ease-in duration-200 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity {{ $destination->is_active ? 'opacity-0' : 'opacity-100' }}" aria-hidden="true">
                                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                            <span class="opacity-0 ease-out duration-100 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity {{ $destination->is_active ? 'opacity-100' : 'opacity-0' }}" aria-hidden="true">
                                                <svg class="h-3 w-3 text-blue-600" fill="currentColor" viewBox="0 0 12 12">
                                                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"/>
                                                </svg>
                                            </span>
                                        </span>
                                    </button>
                                    <span class="ml-2 text-sm {{ $destination->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $destination->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Created Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $destination->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $destination->created_at->format('H:i') }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.destinations.show', $destination) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded" title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.destinations.edit', $destination) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 transition-colors p-1 rounded" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <!-- <button onclick="confirmDelete({{ $destination->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50" 
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button> -->
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="text-4xl mb-4">🏝️</div>
                                    <div class="text-lg font-medium mb-2">Tidak ada destinasi ditemukan</div>
                                    <div class="text-sm mb-4">
                                        @if(request()->hasAny(['search', 'category', 'status']))
                                            Coba ubah filter pencarian Anda
                                        @else
                                            Mulai dengan menambahkan destinasi wisata pertama
                                        @endif
                                    </div>
                                    @if(request()->hasAny(['search', 'category', 'status']))
                                        <a href="{{ route('admin.destinations.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            ↻ Reset Filter
                                        </a>
                                    @else
                                        <a href="{{ route('admin.destinations.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            ➕ Tambah Destinasi Pertama
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($destinations->hasPages())
            <div class="bg-gray-50 px-6 py-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan {{ $destinations->firstItem() ?? 0 }} sampai {{ $destinations->lastItem() ?? 0 }} 
                        dari {{ $destinations->total() }} destinasi
                    </div>
                    <div>
                        {{ $destinations->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Enhanced Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 transform transition-all">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </div>
        
        <div class="text-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus Destinasi</h3>
            <div id="delete-preview" class="mb-4">
                <!-- Preview content akan dimuat disini -->
            </div>
            <p class="text-sm text-gray-600">
                Destinasi akan dipindahkan ke trash dan dapat dipulihkan dalam 30 hari.
                <br><strong>File gambar akan dihapus permanen.</strong>
            </p>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                ❌ Batal
            </button>
            <button onclick="executeDelete()" 
                    id="confirm-delete-btn"
                    class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                🗑️ Hapus Sekarang
            </button>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div id="bulk-delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-lg w-full p-6 transform transition-all">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </div>
        
        <div class="text-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Bulk Delete</h3>
            <p class="text-sm text-gray-600 mb-4">
                Anda akan menghapus <strong id="bulk-count">0</strong> destinasi sekaligus.
                <br>Semua file gambar akan dihapus permanen.
            </p>
            
            <div id="bulk-preview" class="max-h-40 overflow-y-auto mb-4 text-left bg-gray-50 rounded-lg p-3">
                <!-- Bulk preview content -->
            </div>
            
            <!-- Type to confirm -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ketik <strong>"HAPUS"</strong> untuk mengkonfirmasi:
                </label>
                <input type="text" id="bulk-confirm-text" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                       placeholder="Ketik HAPUS">
            </div>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="closeBulkDeleteModal()" 
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                ❌ Batal
            </button>
            <button onclick="executeBulkDelete()" 
                    id="confirm-bulk-delete-btn"
                    class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors opacity-50 cursor-not-allowed"
                    disabled>
                🗑️ Hapus Semua
            </button>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
        <p class="text-gray-600">Memproses permintaan...</p>
    </div>
</div>

<script>
// Global variables
let selectedDestinationId = null;
let selectedDestinations = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Delete functionality initialized');
    
    // Test CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.warn('CSRF token not found! Add <meta name="csrf-token" content="{{ csrf_token() }}"> to layout');
    }
    
    // Test route
    console.log('Delete preview route test URL: /admin/destinations/1/delete-preview');
    
    setupBulkSelection();
    setupBulkConfirmation();
    
    // Auto-hide alerts after 10 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('[id$="-alert"]');
        alerts.forEach(alert => {
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 10000);
});

// === BULK SELECTION FUNCTIONALITY ===
function setupBulkSelection() {
    const selectAllCheckbox = document.getElementById('select-all');
    const destinationCheckboxes = document.querySelectorAll('.destination-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    
    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            destinationCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }
    
    // Individual checkbox functionality
    destinationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
            
            // Update select-all checkbox state
            const checkedCount = document.querySelectorAll('.destination-checkbox:checked').length;
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount === destinationCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < destinationCheckboxes.length;
            }
        });
    });
}

function updateBulkActions() {
    const checkedCheckboxes = document.querySelectorAll('.destination-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    
    selectedDestinations = Array.from(checkedCheckboxes).map(cb => cb.value);
    
    if (selectedDestinations.length > 0) {
        bulkActions?.classList.remove('hidden');
        bulkActions?.classList.add('flex');
    } else {
        bulkActions?.classList.add('hidden');
        bulkActions?.classList.remove('flex');
    }
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.destination-checkbox, #select-all');
    checkboxes.forEach(cb => cb.checked = false);
    selectedDestinations = [];
    updateBulkActions();
}

function setupBulkConfirmation() {
    const confirmText = document.getElementById('bulk-confirm-text');
    const confirmBtn = document.getElementById('confirm-bulk-delete-btn');
    
    if (confirmText && confirmBtn) {
        confirmText.addEventListener('input', function() {
            if (this.value.toUpperCase() === 'HAPUS') {
                confirmBtn.disabled = false;
                confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                confirmBtn.disabled = true;
                confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    }
}

// === SINGLE DELETE FUNCTIONALITY ===
async function confirmDelete(destinationId) {
    selectedDestinationId = destinationId;
    
    try {
        // Show loading
        showLoadingModal();
        
        // Fetch destination preview data
        const response = await fetch(`/admin/destinations/${destinationId}/delete-preview`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });
        
        hideLoadingModal();
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.message || 'Gagal memuat preview destinasi');
        }
        
        const data = result.data;
        
        // Populate delete preview
        const preview = document.getElementById('delete-preview');
        preview.innerHTML = `
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <img src="${data.main_image_url}" alt="${data.name}" 
                     class="w-12 h-12 rounded-lg object-cover"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="text-left">
                    <div class="font-medium text-gray-900">${data.name}</div>
                    <div class="text-sm text-gray-600">${data.category.icon} ${data.category.name}</div>
                    <div class="text-xs text-gray-500">Dibuat: ${data.created_at}</div>
                    ${data.gallery_count > 0 ? `<div class="text-xs text-blue-600">📸 ${data.gallery_count} foto gallery</div>` : ''}
                </div>
            </div>
        `;
        
        // Show modal
        document.getElementById('delete-modal').classList.remove('hidden');
        document.getElementById('delete-modal').classList.add('flex');
        
    } catch (error) {
        hideLoadingModal();
        console.error('Delete preview error:', error);
        
        // Show user-friendly error message
        showNotification(`Gagal memuat preview destinasi: ${error.message}`, 'error');
        
        // Still show modal with basic info
        const preview = document.getElementById('delete-preview');
        preview.innerHTML = `
            <div class="p-3 bg-red-50 rounded-lg border border-red-200">
                <div class="text-center text-red-600">
                    <div class="font-medium">Preview tidak tersedia</div>
                    <div class="text-sm">Destinasi ID: ${destinationId}</div>
                </div>
            </div>
        `;
        
        document.getElementById('delete-modal').classList.remove('hidden');
        document.getElementById('delete-modal').classList.add('flex');
    }
}

async function executeDelete() {
    if (!selectedDestinationId) return;
    
    try {
        showLoadingModal();
        closeDeleteModal();
        
        const response = await fetch(`/admin/destinations/${selectedDestinationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });
        
        hideLoadingModal();
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            
            // Remove row from table with animation
            const row = document.querySelector(`tr[data-destination-id="${selectedDestinationId}"]`);
            if (row) {
                row.style.transition = 'opacity 0.5s, transform 0.5s';
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    row.remove();
                    // Update counters if they exist
                    updateStatistics();
                }, 500);
            } else {
                // If row not found, reload page
                setTimeout(() => location.reload(), 1000);
            }
            
        } else {
            throw new Error(result.message || 'Gagal menghapus destinasi');
        }
        
    } catch (error) {
        hideLoadingModal();
        console.error('Delete execution error:', error);
        showNotification(`Gagal menghapus destinasi: ${error.message}`, 'error');
    }
    
    selectedDestinationId = null;
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.getElementById('delete-modal').classList.remove('flex');
}

// === BULK DELETE FUNCTIONALITY ===
async function bulkDelete() {
    if (selectedDestinations.length === 0) {
        alert('Pilih destinasi yang ingin dihapus terlebih dahulu');
        return;
    }
    
    try {
        showLoadingModal();
        
        // Fetch bulk preview data
        const response = await fetch('/admin/destinations/bulk-preview', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                destination_ids: selectedDestinations
            })
        });
        
        hideLoadingModal();
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.message || 'Gagal memuat preview bulk delete');
        }
        
        const data = result.data;
        
        // Update bulk count
        document.getElementById('bulk-count').textContent = data.count;
        
        // Populate bulk preview
        const preview = document.getElementById('bulk-preview');
        preview.innerHTML = data.destinations.map(dest => `
            <div class="flex items-center space-x-2 py-2 border-b border-gray-200 last:border-b-0">
                <img src="${dest.main_image_url}" alt="${dest.name}" 
                     class="w-8 h-8 rounded object-cover"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="flex-1">
                    <div class="text-sm font-medium">${dest.name}</div>
                    <div class="text-xs text-gray-600">${dest.category.icon} ${dest.category.name}</div>
                </div>
                <span class="text-xs ${dest.is_active ? 'text-green-600' : 'text-red-600'}">
                    ${dest.is_active ? '✅' : '❌'}
                </span>
            </div>
        `).join('');
        
        // Reset confirmation
        document.getElementById('bulk-confirm-text').value = '';
        document.getElementById('confirm-bulk-delete-btn').disabled = true;
        document.getElementById('confirm-bulk-delete-btn').classList.add('opacity-50', 'cursor-not-allowed');
        
        // Show modal
        document.getElementById('bulk-delete-modal').classList.remove('hidden');
        document.getElementById('bulk-delete-modal').classList.add('flex');
        
    } catch (error) {
        hideLoadingModal();
        console.error('Bulk delete preview error:', error);
        showNotification(`Gagal memuat preview bulk delete: ${error.message}`, 'error');
    }
}

async function executeBulkDelete() {
    if (selectedDestinations.length === 0) return;
    
    try {
        showLoadingModal();
        closeBulkDeleteModal();
        
        const response = await fetch('/admin/destinations/bulk-delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                destination_ids: selectedDestinations,
                action: 'soft_delete'
            })
        });
        
        hideLoadingModal();
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            
            // Remove rows from table with staggered animation
            selectedDestinations.forEach((id, index) => {
                setTimeout(() => {
                    const row = document.querySelector(`[data-destination-id="${id}"]`);
                    if (row) {
                        row.style.transition = 'opacity 0.5s, transform 0.5s';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-20px)';
                        setTimeout(() => row.remove(), 500);
                    }
                }, index * 100); // Stagger the animations
            });
            
            // Clear selection
            setTimeout(() => clearSelection(), selectedDestinations.length * 100 + 500);
            
            // Update counters
            setTimeout(() => updateStatistics(), 1000);
            
            // Show errors if any
            if (result.errors && result.errors.length > 0) {
                setTimeout(() => {
                    showNotification(`${result.errors.length} destinasi gagal dihapus. Check console untuk detail.`, 'error');
                    console.error('Bulk delete errors:', result.errors);
                }, 1000);
            }
            
        } else {
            throw new Error(result.message || 'Gagal melakukan bulk delete');
        }
        
    } catch (error) {
        hideLoadingModal();
        console.error('Bulk delete execution error:', error);
        showNotification(`Gagal melakukan bulk delete: ${error.message}`, 'error');
    }
}

function closeBulkDeleteModal() {
    document.getElementById('bulk-delete-modal').classList.add('hidden');
    document.getElementById('bulk-delete-modal').classList.remove('flex');
}

// === UTILITY FUNCTIONS ===
function showLoadingModal() {
    let modal = document.getElementById('loading-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function hideLoadingModal() {
    const modal = document.getElementById('loading-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(n => n.remove());
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm transform translate-x-full transition-transform duration-300 ${
        type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
        type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
        'bg-blue-100 border border-blue-400 text-blue-700'
    }`;
    
    const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️';
    
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="text-lg mr-2">${icon}</span>
                <span class="text-sm font-medium">${message}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification with animation
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 10);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

function updateStatistics() {
    // Update counters on page
    setTimeout(() => {
        location.reload();
    }, 2000);
}

// === TOGGLE STATUS FUNCTIONALITY ===
async function toggleStatus(destinationId, newStatus) {
    try {
        const response = await fetch(`/admin/destinations/${destinationId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                is_active: newStatus === 'true'
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            // The toggle switch will update automatically due to the onclick handler
        } else {
            throw new Error(result.message || 'Gagal mengubah status destinasi');
        }

    } catch (error) {
        console.error('Toggle status error:', error);
        showNotification(`Gagal mengubah status: ${error.message}`, 'error');
        // Revert the toggle switch
        location.reload();
    }
}

// === ALERT CLOSE FUNCTIONALITY ===
function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }
}

// === UNDO DELETE FUNCTIONALITY ===
function undoDelete() {
    // This would need to be implemented based on your restore functionality
    showNotification('Fitur undo sedang dalam pengembangan', 'info');
}

// === KEYBOARD SHORTCUTS ===
document.addEventListener('keydown', function(e) {
    // Escape key closes modals
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeBulkDeleteModal();
        hideLoadingModal();
    }
});

// === DEBUG FUNCTION ===
function debugRoutes() {
    console.log('Available routes for testing:');
    console.log('Delete preview:', `/admin/destinations/1/delete-preview`);
    console.log('Bulk preview:', `/admin/destinations/bulk-preview`);
    console.log('Delete:', `/admin/destinations/1`);
    console.log('Bulk delete:', `/admin/destinations/bulk-delete`);
    console.log('Toggle status:', `/admin/destinations/1/toggle-status`);
}
</script>

@endsection