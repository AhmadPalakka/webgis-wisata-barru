@extends('admin.layouts.app')

@section('title', 'Trash - Destinasi Terhapus')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.destinations.index') }}" 
                   class="text-gray-500 hover:text-gray-700 transition-colors">
                    ← Kembali ke Daftar Destinasi
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🗑️ Trash - Destinasi Terhapus</h1>
            <p class="text-gray-600">Destinasi yang telah dihapus dapat dipulihkan dalam 30 hari</p>
        </div>
        
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            <!-- Bulk Restore Actions (Initially Hidden) -->
            <div id="bulk-restore-actions" class="hidden flex gap-2">
                <button onclick="bulkRestore()" 
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                    ↩️ Pulihkan Terpilih
                </button>
                <button onclick="bulkForceDelete()" 
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                    ⚠️ Hapus Permanen
                </button>
                <button onclick="clearSelection()" 
                        class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ❌ Batal
                </button>
            </div>
            
            <!-- Cleanup Actions -->
            <button onclick="cleanupOrphanedImages()" 
                   class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                🧹 Bersihkan File
            </button>
            
            <button onclick="confirmEmptyTrash()" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                🗑️ Kosongkan Trash
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="bg-gray-100 p-3 rounded-full mr-3">
                    <span class="text-2xl">🗑️</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $destinations->total() }}</div>
                    <div class="text-gray-600 text-sm">Total Terhapus</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full mr-3">
                    <span class="text-2xl">⏱️</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ $destinations->where('deleted_at', '>', now()->subDays(7))->count() }}
                    </div>
                    <div class="text-gray-600 text-sm">7 Hari Terakhir</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-full mr-3">
                    <span class="text-2xl">⚠️</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-600">
                        {{ $destinations->where('deleted_at', '<', now()->subDays(23))->count() }}
                    </div>
                    <div class="text-gray-600 text-sm">Kadaluarsa < 7 Hari</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <span class="text-2xl">💾</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-blue-600">
                        {{ round(collect(Storage::disk('public')->files('images/destinations'))->sum(function($file) { return Storage::disk('public')->size($file); }) / 1024 / 1024, 1) }}
                    </div>
                    <div class="text-gray-600 text-sm">MB File Gambar</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.destinations.trashed') }}" class="space-y-4 md:space-y-0 md:flex md:items-center md:space-x-4">
            <!-- Search -->
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="🔍 Cari destinasi terhapus..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Category Filter -->
            <div>
                <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach($categories as $key => $category)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                            {{ $category['icon'] }} {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Date Filter -->
            <div>
                <select name="date_range" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" {{ request('date_range') == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="expiring" {{ request('date_range') == 'expiring' ? 'selected' : '' }}>⚠️ Akan Kadaluarsa</option>
                </select>
            </div>
            
            <!-- Filter Button -->
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    🔍 Filter
                </button>
                @if(request()->hasAny(['search', 'category', 'date_range']))
                    <a href="{{ route('admin.destinations.trashed') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        ↻ Reset
                    </a>
                @endif
            </div>
        </form>
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
                            Dihapus Pada
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kadaluarsa
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($destinations as $destination)
                        @php
                            $daysLeft = 30 - $destination->deleted_at->diffInDays(now());
                            $isExpiringSoon = $daysLeft <= 7;
                        @endphp
                        <tr class="hover:bg-gray-50 destination-row {{ $isExpiringSoon ? 'bg-red-50' : '' }}" data-destination-id="{{ $destination->id }}">
                            <!-- Bulk Select Checkbox -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="destination-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                       value="{{ $destination->id }}">
                            </td>
                            
                            <!-- Destination Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <img class="h-16 w-16 rounded-lg object-cover opacity-75" 
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
                                        @if($destination->gallery && is_array($destination->gallery))
                                            <div class="text-xs text-blue-600 mt-1">📸 {{ count($destination->gallery) }} foto gallery</div>
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

                            <!-- Deleted Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $destination->deleted_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $destination->deleted_at->format('H:i') }}</div>
                                <div class="text-xs text-gray-400">{{ $destination->deleted_at->diffForHumans() }}</div>
                            </td>

                            <!-- Expiry Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($daysLeft > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isExpiringSoon ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $isExpiringSoon ? '⚠️' : '⏱️' }} {{ $daysLeft }} hari lagi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        ❌ Kadaluarsa
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <!-- Restore Button -->
                                    <button onclick="restoreDestination({{ $destination->id }})" 
                                            class="text-green-600 hover:text-green-900 transition-colors" title="Pulihkan">
                                        ↩️
                                    </button>
                                    
                                    <!-- Preview Button -->
                                    <button onclick="previewDestination({{ $destination->id }})" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors" title="Preview">
                                        👁️
                                    </button>
                                    
                                    <!-- Force Delete Button -->
                                    <button onclick="confirmForceDelete({{ $destination->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors" title="Hapus Permanen">
                                        ⚠️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="text-4xl mb-4">🗑️</div>
                                    <div class="text-lg font-medium mb-2">Trash kosong</div>
                                    <div class="text-sm mb-4">
                                        @if(request()->hasAny(['search', 'category', 'date_range']))
                                            Tidak ada destinasi terhapus yang cocok dengan filter
                                        @else
                                            Belum ada destinasi yang dihapus
                                        @endif
                                    </div>
                                    @if(request()->hasAny(['search', 'category', 'date_range']))
                                        <a href="{{ route('admin.destinations.trashed') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            ↻ Reset Filter
                                        </a>
                                    @else
                                        <a href="{{ route('admin.destinations.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            ← Kembali ke Daftar Destinasi
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
                        dari {{ $destinations->total() }} destinasi terhapus
                    </div>
                    <div>
                        {{ $destinations->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Restore Confirmation Modal -->
<div id="restore-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 transform transition-all">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </div>
        
        <div class="text-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Pulihkan Destinasi</h3>
            <div id="restore-preview" class="mb-4">
                <!-- Preview content will be loaded here -->
            </div>
            <p class="text-sm text-gray-600">
                Destinasi akan dipulihkan dan dapat diakses kembali seperti semula.
            </p>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="closeRestoreModal()" 
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                ❌ Batal
            </button>
            <button onclick="executeRestore()" 
                    id="confirm-restore-btn"
                    class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                ↩️ Pulihkan Sekarang
            </button>
        </div>
    </div>
</div>

<!-- Force Delete Confirmation Modal -->
<div id="force-delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 transform transition-all">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.084 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        
        <div class="text-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">⚠️ Hapus Permanen</h3>
            <div id="force-delete-preview" class="mb-4">
                <!-- Preview content will be loaded here -->
            </div>
            <p class="text-sm text-red-600 font-medium mb-2">
                PERINGATAN: Tindakan ini tidak dapat dibatalkan!
            </p>
            <p class="text-sm text-gray-600">
                Destinasi dan semua file gambar akan dihapus secara permanen dari server.
            </p>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="closeForceDeleteModal()" 
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                ❌ Batal
            </button>
            <button onclick="executeForceDelete()" 
                    id="confirm-force-delete-btn"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                ⚠️ Hapus Permanen
            </button>
        </div>
    </div>
</div>

<!-- Empty Trash Confirmation Modal -->
<div id="empty-trash-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-lg w-full p-6 transform transition-all">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </div>
        
        <div class="text-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">⚠️ Kosongkan Trash</h3>
            <p class="text-sm text-red-600 font-medium mb-2">
                PERINGATAN: Semua destinasi di trash akan dihapus permanen!
            </p>
            <p class="text-sm text-gray-600 mb-4">
                Total {{ $destinations->total() }} destinasi dan semua file gambar akan dihapus secara permanen.
                Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <!-- Type to confirm -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ketik <strong>"KOSONGKAN TRASH"</strong> untuk mengkonfirmasi:
                </label>
                <input type="text" id="empty-trash-confirm-text" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                       placeholder="Ketik KOSONGKAN TRASH">
            </div>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="closeEmptyTrashModal()" 
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                ❌ Batal
            </button>
            <button onclick="executeEmptyTrash()" 
                    id="confirm-empty-trash-btn"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors opacity-50 cursor-not-allowed"
                    disabled>
                🗑️ Kosongkan Trash
            </button>
        </div>
    </div>
</div>

<!-- Bulk Actions Modals -->
<div id="bulk-restore-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-lg w-full p-6 transform transition-all">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </div>
        
        <div class="text-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Bulk Restore</h3>
            <p class="text-sm text-gray-600 mb-4">
                Anda akan memulihkan <strong id="bulk-restore-count">0</strong> destinasi sekaligus.
            </p>
            
            <div id="bulk-restore-preview" class="max-h-40 overflow-y-auto mb-4 text-left bg-gray-50 rounded-lg p-3">
                <!-- Bulk restore preview content -->
            </div>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="closeBulkRestoreModal()" 
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                ❌ Batal
            </button>
            <button onclick="executeBulkRestore()" 
                    id="confirm-bulk-restore-btn"
                    class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                ↩️ Pulihkan Semua
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
    setupBulkSelection();
    setupEmptyTrashConfirmation();
    
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
    const bulkActions = document.getElementById('bulk-restore-actions');
    
    // Select all functionality
    selectAllCheckbox?.addEventListener('change', function() {
        destinationCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedDestinations();
    });
    
    // Individual checkbox change
    destinationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedDestinations);
    });
}

function updateSelectedDestinations() {
    const selectedCheckboxes = document.querySelectorAll('.destination-checkbox:checked');
    selectedDestinations = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    const bulkActions = document.getElementById('bulk-restore-actions');
    const selectAllCheckbox = document.getElementById('select-all');
    
    if (selectedDestinations.length > 0) {
        bulkActions?.classList.remove('hidden');
        bulkActions?.classList.add('flex');
    } else {
        bulkActions?.classList.add('hidden');
        bulkActions?.classList.remove('flex');
    }
    
    // Update select all checkbox state
    const allCheckboxes = document.querySelectorAll('.destination-checkbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = selectedDestinations.length === allCheckboxes.length;
        selectAllCheckbox.indeterminate = selectedDestinations.length > 0 && selectedDestinations.length < allCheckboxes.length;
    }
}

function clearSelection() {
    document.querySelectorAll('.destination-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('select-all').checked = false;
    updateSelectedDestinations();
}

// === RESTORE FUNCTIONALITY ===
async function restoreDestination(destinationId) {
    selectedDestinationId = destinationId;
    
    try {
        showLoadingModal();
        
        // Fetch destination preview data
        const response = await fetch(`/api/admin/destinations/${destinationId}/delete-preview`);
        const data = await response.json();
        
        hideLoadingModal();
        
        // Populate restore preview
        const preview = document.getElementById('restore-preview');
        preview.innerHTML = `
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <img src="${data.main_image_url}" alt="${data.name}" 
                     class="w-12 h-12 rounded-lg object-cover"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="text-left">
                    <div class="font-medium text-gray-900">${data.name}</div>
                    <div class="text-sm text-gray-600">${data.category.icon} ${data.category.name}</div>
                    ${data.gallery_count > 0 ? `<div class="text-xs text-blue-600">📸 ${data.gallery_count} foto gallery</div>` : ''}
                </div>
            </div>
        `;
        
        // Show modal
        document.getElementById('restore-modal').classList.remove('hidden');
        document.getElementById('restore-modal').classList.add('flex');
        
    } catch (error) {
        hideLoadingModal();
        alert('Gagal memuat preview destinasi: ' + error.message);
    }
}

async function executeRestore() {
    if (!selectedDestinationId) return;
    
    try {
        showLoadingModal();
        closeRestoreModal();
        
        const response = await fetch(`/admin/destinations/${selectedDestinationId}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });
        
        const result = await response.json();
        hideLoadingModal();
        
        if (result.success) {
            showNotification(result.message, 'success');
            
            // Remove row from table
            const row = document.querySelector(`[data-destination-id="${selectedDestinationId}"]`);
            if (row) {
                row.style.transition = 'opacity 0.5s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 500);
            }
            
            // Update counters
            setTimeout(() => location.reload(), 2000);
            
        } else {
            showNotification(result.message || 'Gagal memulihkan destinasi', 'error');
        }
        
    } catch (error) {
        hideLoadingModal();
        showNotification('Terjadi kesalahan saat memulihkan destinasi', 'error');
        console.error('Restore error:', error);
    }
    
    selectedDestinationId = null;
}

function closeRestoreModal() {
    document.getElementById('restore-modal').classList.add('hidden');
    document.getElementById('restore-modal').classList.remove('flex');
}

// === FORCE DELETE FUNCTIONALITY ===
async function confirmForceDelete(destinationId) {
    selectedDestinationId = destinationId;
    
    try {
        showLoadingModal();
        
        // Fetch destination preview data
        const response = await fetch(`/api/admin/destinations/${destinationId}/delete-preview`);
        const data = await response.json();
        
        hideLoadingModal();
        
        // Populate force delete preview
        const preview = document.getElementById('force-delete-preview');
        preview.innerHTML = `
            <div class="flex items-center space-x-3 p-3 bg-red-50 rounded-lg border border-red-200">
                <img src="${data.main_image_url}" alt="${data.name}" 
                     class="w-12 h-12 rounded-lg object-cover opacity-75"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="text-left">
                    <div class="font-medium text-gray-900">${data.name}</div>
                    <div class="text-sm text-red-600">${data.category.icon} ${data.category.name}</div>
                    ${data.gallery_count > 0 ? `<div class="text-xs text-red-500">⚠️ ${data.gallery_count} file gambar akan dihapus</div>` : ''}
                </div>
            </div>
        `;
        
        // Show modal
        document.getElementById('force-delete-modal').classList.remove('hidden');
        document.getElementById('force-delete-modal').classList.add('flex');
        
    } catch (error) {
        hideLoadingModal();
        alert('Gagal memuat preview destinasi: ' + error.message);
    }
}

async function executeForceDelete() {
    if (!selectedDestinationId) return;
    
    try {
        showLoadingModal();
        closeForceDeleteModal();
        
        const response = await fetch(`/admin/destinations/${selectedDestinationId}/force-delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });
        
        const result = await response.json();
        hideLoadingModal();
        
        if (result.success) {
            showNotification(result.message, 'success');
            
            // Remove row from table
            const row = document.querySelector(`[data-destination-id="${selectedDestinationId}"]`);
            if (row) {
                row.style.transition = 'opacity 0.5s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 500);
            }
            
            // Update counters
            setTimeout(() => location.reload(), 2000);
            
        } else {
            showNotification(result.message || 'Gagal menghapus destinasi secara permanen', 'error');
        }
        
    } catch (error) {
        hideLoadingModal();
        showNotification('Terjadi kesalahan saat menghapus destinasi', 'error');
        console.error('Force delete error:', error);
    }
    
    selectedDestinationId = null;
}

function closeForceDeleteModal() {
    document.getElementById('force-delete-modal').classList.add('hidden');
    document.getElementById('force-delete-modal').classList.remove('flex');
}

// === BULK RESTORE FUNCTIONALITY ===
async function bulkRestore() {
    if (selectedDestinations.length === 0) {
        alert('Pilih destinasi yang ingin dipulihkan terlebih dahulu');
        return;
    }
    
    try {
        showLoadingModal();
        
        // Fetch bulk preview data
        const response = await fetch('/api/admin/destinations/bulk-preview', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                destination_ids: selectedDestinations
            })
        });
        
        const data = await response.json();
        hideLoadingModal();
        
        // Update bulk count
        document.getElementById('bulk-restore-count').textContent = data.count;
        
        // Populate bulk preview
        const preview = document.getElementById('bulk-restore-preview');
        preview.innerHTML = data.destinations.map(dest => `
            <div class="flex items-center space-x-2 py-2 border-b border-gray-200 last:border-b-0">
                <img src="${dest.main_image_url}" alt="${dest.name}" 
                     class="w-8 h-8 rounded object-cover"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="flex-1">
                    <div class="text-sm font-medium">${dest.name}</div>
                    <div class="text-xs text-gray-600">${dest.category.icon} ${dest.category.name}</div>
                </div>
                <span class="text-xs text-green-600">↩️</span>
            </div>
        `).join('');
        
        // Show modal
        document.getElementById('bulk-restore-modal').classList.remove('hidden');
        document.getElementById('bulk-restore-modal').classList.add('flex');
        
    } catch (error) {
        hideLoadingModal();
        alert('Gagal memuat preview bulk restore: ' + error.message);
    }
}

async function executeBulkRestore() {
    if (selectedDestinations.length === 0) return;
    
    try {
        showLoadingModal();
        closeBulkRestoreModal();
        
        const response = await fetch('/admin/destinations/bulk-restore', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                destination_ids: selectedDestinations
            })
        });
        
        const result = await response.json();
        hideLoadingModal();
        
        if (result.success) {
            showNotification(result.message, 'success');
            
            // Remove rows from table
            selectedDestinations.forEach(id => {
                const row = document.querySelector(`[data-destination-id="${id}"]`);
                if (row) {
                    row.style.transition = 'opacity 0.5s';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 500);
                }
            });
            
            // Clear selection
            clearSelection();
            
            // Update counters
            setTimeout(() => location.reload(), 2000);
            
        } else {
            showNotification(result.message || 'Gagal melakukan bulk restore', 'error');
        }
        
    } catch (error) {
        hideLoadingModal();
        showNotification('Terjadi kesalahan saat melakukan bulk restore', 'error');
        console.error('Bulk restore error:', error);
    }
}

function closeBulkRestoreModal() {
    document.getElementById('bulk-restore-modal').classList.add('hidden');
    document.getElementById('bulk-restore-modal').classList.remove('flex');
}

// === BULK FORCE DELETE FUNCTIONALITY ===
async function bulkForceDelete() {
    if (selectedDestinations.length === 0) {
        alert('Pilih destinasi yang ingin dihapus permanen terlebih dahulu');
        return;
    }
    
    if (confirm(`PERINGATAN: Anda akan menghapus ${selectedDestinations.length} destinasi secara PERMANEN!\n\nSemua file gambar akan dihapus dan tidak dapat dipulihkan.\n\nApakah Anda yakin?`)) {
        try {
            showLoadingModal();
            
            const response = await fetch('/admin/destinations/bulk-delete', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    destination_ids: selectedDestinations,
                    action: 'force_delete'
                })
            });
            
            const result = await response.json();
            hideLoadingModal();
            
            if (result.success) {
                showNotification(result.message, 'success');
                
                // Remove rows from table
                selectedDestinations.forEach(id => {
                    const row = document.querySelector(`[data-destination-id="${id}"]`);
                    if (row) {
                        row.style.transition = 'opacity 0.5s';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 500);
                    }
                });
                
                // Clear selection
                clearSelection();
                
                // Update counters
                setTimeout(() => location.reload(), 2000);
                
            } else {
                showNotification(result.message || 'Gagal melakukan bulk force delete', 'error');
            }
            
        } catch (error) {
            hideLoadingModal();
            showNotification('Terjadi kesalahan saat melakukan bulk force delete', 'error');
            console.error('Bulk force delete error:', error);
        }
    }
}

// === EMPTY TRASH FUNCTIONALITY ===
function confirmEmptyTrash() {
    document.getElementById('empty-trash-modal').classList.remove('hidden');
    document.getElementById('empty-trash-modal').classList.add('flex');
}

function setupEmptyTrashConfirmation() {
    const confirmInput = document.getElementById('empty-trash-confirm-text');
    const confirmBtn = document.getElementById('confirm-empty-trash-btn');
    
    confirmInput?.addEventListener('input', function() {
        if (this.value.toUpperCase() === 'KOSONGKAN TRASH') {
            confirmBtn.disabled = false;
            confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });
}

async function executeEmptyTrash() {
    const allTrashedIds = Array.from(document.querySelectorAll('.destination-checkbox')).map(cb => cb.value);
    
    if (allTrashedIds.length === 0) {
        alert('Tidak ada destinasi di trash');
        return;
    }
    
    try {
        showLoadingModal();
        closeEmptyTrashModal();
        
        const response = await fetch('/admin/destinations/bulk-delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                destination_ids: allTrashedIds,
                action: 'force_delete'
            })
        });
        
        const result = await response.json();
        hideLoadingModal();
        
        if (result.success) {
            showNotification('Trash berhasil dikosongkan!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(result.message || 'Gagal mengosongkan trash', 'error');
        }
        
    } catch (error) {
        hideLoadingModal();
        showNotification('Terjadi kesalahan saat mengosongkan trash', 'error');
        console.error('Empty trash error:', error);
    }
}

function closeEmptyTrashModal() {
    document.getElementById('empty-trash-modal').classList.add('hidden');
    document.getElementById('empty-trash-modal').classList.remove('flex');
    // Reset form
    document.getElementById('empty-trash-confirm-text').value = '';
    document.getElementById('confirm-empty-trash-btn').disabled = true;
    document.getElementById('confirm-empty-trash-btn').classList.add('opacity-50', 'cursor-not-allowed');
}

// === IMAGE CLEANUP FUNCTIONALITY ===
async function cleanupOrphanedImages() {
    if (confirm('Apakah Anda yakin ingin membersihkan file gambar yang tidak terpakai?\n\nIni akan menghapus file gambar yang tidak terkait dengan destinasi manapun.')) {
        try {
            showLoadingModal();
            
            const response = await fetch('/admin/destinations/cleanup-orphaned-images', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                }
            });
            
            const result = await response.json();
            hideLoadingModal();
            
            if (result.success) {
                showNotification(result.message, 'success');
            } else {
                showNotification(result.message || 'Gagal membersihkan file gambar', 'error');
            }
            
        } catch (error) {
            hideLoadingModal();
            showNotification('Terjadi kesalahan saat membersihkan file gambar', 'error');
            console.error('Cleanup error:', error);
        }
    }
}

// === PREVIEW FUNCTIONALITY ===
function previewDestination(destinationId) {
    // This could open a modal with destination preview
    // For now, we'll just show an alert
    alert('Preview functionality - akan menampilkan detail destinasi dalam modal');
}

// === UTILITY FUNCTIONS ===
function showLoadingModal() {
    document.getElementById('loading-modal').classList.remove('hidden');
    document.getElementById('loading-modal').classList.add('flex');
}

function hideLoadingModal() {
    document.getElementById('loading-modal').classList.add('hidden');
    document.getElementById('loading-modal').classList.remove('flex');
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(n => n.remove());
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm ${
        type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
        type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
        'bg-blue-100 border border-blue-400 text-blue-700'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium">${message}</span>
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
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// === KEYBOARD SHORTCUTS ===
document.addEventListener('keydown', function(e) {
    // Escape key closes modals
    if (e.key === 'Escape') {
        closeRestoreModal();
        closeForceDeleteModal();
        closeEmptyTrashModal();
        closeBulkRestoreModal();
        hideLoadingModal();
    }
    
    // Ctrl+A selects all (prevent default and use our custom logic)
    if (e.ctrlKey && e.key === 'a' && document.activeElement.tagName !== 'INPUT') {
        e.preventDefault();
        document.getElementById('select-all').checked = true;
        document.getElementById('select-all').dispatchEvent(new Event('change'));
    }
    
    // R key for restore (when destinations selected)
    if (e.key === 'r' && selectedDestinations.length > 0 && !e.ctrlKey && !e.metaKey) {
        e.preventDefault();
        bulkRestore();
    }
});
</script>

@endsection