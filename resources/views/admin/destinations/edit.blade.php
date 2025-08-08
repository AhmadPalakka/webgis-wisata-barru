@extends('admin.layouts.app')

@section('title', 'Edit ' . $destination->name)

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
            <li>
                <div class="flex items-center">
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('admin.destinations.show', $destination) }}" class="text-gray-600 hover:text-gray-900">
                        {{ Str::limit($destination->name, 20) }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-500">Edit</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">✏️ Edit Destinasi Wisata</h1>
                <p class="text-gray-600">Perbarui informasi destinasi <strong>{{ $destination->name }}</strong></p>
            </div>
            <div class="flex space-x-3 mt-4 lg:mt-0">
                <a href="{{ route('admin.destinations.show', $destination) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    👁️ Lihat Detail
                </a>
                <a href="{{ route('destinations.show', $destination->slug) }}" target="_blank"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    🌐 Preview Website
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data" id="edit-form">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">📝 Informasi Dasar</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="form-label">Nama Destinasi <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $destination->name) }}"
                                   class="form-input @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">
                                Slug akan otomatis dibuat: <span id="slug-preview" class="font-mono text-blue-600">{{ $destination->slug }}</span>
                            </p>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="form-label">Kategori <span class="text-red-500">*</span></label>
                            <select id="category" 
                                    name="category" 
                                    class="form-select @error('category') border-red-500 @enderror" 
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $key => $category)
                                    <option value="{{ $key }}" 
                                            {{ old('category', $destination->category) === $key ? 'selected' : '' }}>
                                        {{ $category['icon'] }} {{ $category['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="form-label">Deskripsi <span class="text-red-500">*</span></label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="5"
                                      class="form-textarea @error('description') border-red-500 @enderror"
                                      required>{{ old('description', $destination->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">
                                <span id="char-count">{{ strlen($destination->description) }}</span> karakter
                            </p>
                        </div>

                        <!-- Address & Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" 
                                       id="address" 
                                       name="address" 
                                       value="{{ old('address', $destination->address) }}"
                                       class="form-input @error('address') border-red-500 @enderror"
                                       placeholder="Contoh: Kefamenanu, TTU">
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact" class="form-label">Kontak</label>
                                <input type="text" 
                                       id="contact" 
                                       name="contact" 
                                       value="{{ old('contact', $destination->contact) }}"
                                       class="form-input @error('contact') border-red-500 @enderror"
                                       placeholder="Contoh: +62 812-3456-7890">
                                @error('contact')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">📍 Lokasi & Koordinat</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Latitude -->
                            <div>
                                <label for="latitude" class="form-label">Latitude <span class="text-red-500">*</span></label>
                                <input type="number" 
                                       id="latitude" 
                                       name="latitude" 
                                       value="{{ old('latitude', $destination->latitude) }}"
                                       step="any"
                                       class="form-input @error('latitude') border-red-500 @enderror"
                                       required>
                                @error('latitude')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Longitude -->
                            <div>
                                <label for="longitude" class="form-label">Longitude <span class="text-red-500">*</span></label>
                                <input type="number" 
                                       id="longitude" 
                                       name="longitude" 
                                       value="{{ old('longitude', $destination->longitude) }}"
                                       step="any"
                                       class="form-input @error('longitude') border-red-500 @enderror"
                                       required>
                                @error('longitude')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Coordinate Validation & Preview -->
                        <div id="coordinate-validation" class="hidden">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p id="coordinate-message" class="text-sm text-blue-800"></p>
                                        <a id="google-maps-link" href="#" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 underline mt-1 inline-block">
                                            📍 Lihat di Google Maps
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Location Button -->
                        <div class="flex justify-between items-center">
                            <button type="button" 
                                    id="get-location-btn"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                🌍 Gunakan Lokasi Saat Ini
                            </button>
                            
                            <div class="text-sm text-gray-500">
                                Koordinat saat ini: 
                                <span class="font-mono">{{ number_format($destination->latitude, 6) }}, {{ number_format($destination->longitude, 6) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">📷 Gambar & Galeri</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Current Main Image -->
                        <div>
                            <label class="form-label">Gambar Utama Saat Ini</label>
                            <div class="mt-2">
                                @if($destination->main_image && $imageExists)
                                    <div class="relative inline-block">
                                        <img src="{{ asset('images/destinations/' . $destination->main_image) }}" 
                                             alt="{{ $destination->name }}"
                                             class="w-48 h-36 object-cover rounded-lg shadow-md">
                                        <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                            {{ $destination->main_image }}
                                        </div>
                                    </div>
                                @else
                                    <div class="w-48 h-36 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-500">Gambar tidak ditemukan</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Upload New Main Image -->
                        <div>
                            <label for="main_image" class="form-label">Upload Gambar Utama Baru</label>
                            <div class="mt-2">
                                <div class="image-upload-area" id="main-image-drop-area">
                                    <input type="file" 
                                           id="main_image" 
                                           name="main_image" 
                                           accept="image/jpeg,image/jpg,image/png"
                                           class="hidden">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="mt-4">
                                            <label for="main_image" class="cursor-pointer">
                                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                                    Klik untuk upload atau drag & drop
                                                </span>
                                                <span class="mt-1 block text-sm text-gray-500">
                                                    PNG, JPG hingga 2MB
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Preview area for new main image -->
                                <div id="main-image-preview" class="hidden mt-4">
                                    <div class="relative inline-block">
                                        <img id="main-image-preview-img" src="" class="w-48 h-36 object-cover rounded-lg shadow-md">
                                        <button type="button" 
                                                id="remove-main-image-preview"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('main_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">
                                Kosongkan jika tidak ingin mengganti gambar utama
                            </p>
                        </div>

                        <!-- Current Gallery -->
                        @if(!empty($galleryImages))
                        <div>
                            <label class="form-label">Galeri Saat Ini</label>
                            <div class="gallery-grid mt-2">
                                @foreach($galleryImages as $index => $image)
                                <div class="gallery-item">
                                    @if($image['exists'])
                                        <img src="{{ $image['url'] }}" 
                                             alt="Gallery {{ $index + 1 }}"
                                             class="w-full h-32 object-cover">
                                        <div class="gallery-overlay">
                                            <div class="flex items-center space-x-2">
                                                <button type="button" 
                                                        class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600"
                                                        onclick="removeFromGallery('{{ $image['filename'] }}', this)">
                                                    🗑️ Hapus
                                                </button>
                                            </div>
                                        </div>
                                        <input type="checkbox" 
                                               name="remove_gallery[]" 
                                               value="{{ $image['filename'] }}" 
                                               class="remove-gallery-checkbox hidden">
                                    @else
                                        <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500 text-xs">Gambar tidak ditemukan</span>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-2 left-2 right-2 bg-black bg-opacity-50 text-white px-1 py-0.5 rounded text-xs truncate">
                                        {{ $image['filename'] }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div id="gallery-remove-notice" class="hidden mt-2 p-2 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                                <span id="gallery-remove-count">0</span> gambar akan dihapus saat form disimpan.
                            </div>
                        </div>
                        @endif

                        <!-- Upload New Gallery Images -->
                        <div>
                            <label for="gallery" class="form-label">Tambah Gambar ke Galeri</label>
                            <div class="mt-2">
                                <div class="image-upload-area" id="gallery-drop-area">
                                    <input type="file" 
                                           id="gallery" 
                                           name="gallery[]" 
                                           accept="image/jpeg,image/jpg,image/png"
                                           multiple
                                           class="hidden">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="mt-4">
                                            <label for="gallery" class="cursor-pointer">
                                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                                    Upload multiple gambar
                                                </span>
                                                <span class="mt-1 block text-sm text-gray-500">
                                                    Pilih beberapa file sekaligus (PNG, JPG hingga 2MB per file)
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Preview area for new gallery images -->
                                <div id="gallery-preview" class="hidden mt-4">
                                    <div id="gallery-preview-grid" class="gallery-grid"></div>
                                </div>
                            </div>
                            @error('gallery.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">ℹ️ Informasi Tambahan</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Operating Hours -->
                        <div>
                            <label for="operating_hours" class="form-label">Jam Operasional</label>
                            <input type="text" 
                                   id="operating_hours" 
                                   name="operating_hours" 
                                   value="{{ old('operating_hours', $destination->operating_hours ?? '24 jam') }}"
                                   class="form-input @error('operating_hours') border-red-500 @enderror"
                                   placeholder="Contoh: 08:00 - 17:00">
                            @error('operating_hours')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Entry Fees -->
                        <div>
                            <label class="form-label">Tarif Masuk</label>
                            <div id="entry-fees-container" class="space-y-2">
                                @if($destination->entry_fees)
                                    @foreach($destination->entry_fees as $vehicle => $fee)
                                    <div class="entry-fee-item flex items-center space-x-2">
                                        <input type="text" 
                                               name="entry_fees[{{ $loop->index }}][vehicle]" 
                                               value="{{ $vehicle }}"
                                               placeholder="Jenis kendaraan" 
                                               class="form-input flex-1">
                                        <span class="text-gray-500">Rp</span>
                                        <input type="number" 
                                               name="entry_fees[{{ $loop->index }}][fee]" 
                                               value="{{ $fee }}"
                                               placeholder="0" 
                                               class="form-input w-32">
                                        <button type="button" 
                                                onclick="removeEntryFee(this)" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded">
                                            🗑️
                                        </button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="entry-fee-item flex items-center space-x-2">
                                        <input type="text" 
                                               name="entry_fees[0][vehicle]" 
                                               placeholder="Jenis kendaraan (opsional)" 
                                               class="form-input flex-1">
                                        <span class="text-gray-500">Rp</span>
                                        <input type="number" 
                                               name="entry_fees[0][fee]" 
                                               placeholder="0" 
                                               class="form-input w-32">
                                        <button type="button" 
                                                onclick="removeEntryFee(this)" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded">
                                            🗑️
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" 
                                    onclick="addEntryFee()" 
                                    class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                ➕ Tambah Tarif
                            </button>
                            <p class="text-gray-500 text-sm mt-1">Kosongkan semua jika gratis</p>
                        </div>

                        <!-- Activities -->
                        <div>
                            <label class="form-label">Aktivitas yang Bisa Dilakukan</label>
                            <div id="activities-container" class="space-y-2">
                                @if($destination->activities)
                                    @foreach($destination->activities as $activity)
                                    <div class="activity-item flex items-center space-x-2">
                                        <input type="text" 
                                               name="activities[]" 
                                               value="{{ $activity }}"
                                               placeholder="Nama aktivitas" 
                                               class="form-input flex-1">
                                        <button type="button" 
                                                onclick="removeActivity(this)" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded">
                                            🗑️
                                        </button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="activity-item flex items-center space-x-2">
                                        <input type="text" 
                                               name="activities[]" 
                                               placeholder="Nama aktivitas (opsional)" 
                                               class="form-input flex-1">
                                        <button type="button" 
                                                onclick="removeActivity(this)" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded">
                                            🗑️
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" 
                                    onclick="addActivity()" 
                                    class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                ➕ Tambah Aktivitas
                            </button>
                        </div>

                        <!-- Visitor Info -->
                        <div>
                            <label for="visitor_info" class="form-label">Informasi untuk Pengunjung</label>
                            <textarea id="visitor_info" 
                                      name="visitor_info" 
                                      rows="3"
                                      class="form-textarea @error('visitor_info') border-red-500 @enderror"
                                      placeholder="Tips, aturan, atau informasi penting lainnya">{{ old('visitor_info', $destination->visitor_info) }}</textarea>
                            @error('visitor_info')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Status & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">⚙️ Status & Pengaturan</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Active Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $destination->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">
                                    <strong>Aktifkan destinasi</strong><br>
                                    <span class="text-gray-500">Destinasi akan tampil di website publik</span>
                                </span>
                            </label>
                        </div>

                        <!-- Current Status Info -->
                        <div class="border-t pt-4">
                            <div class="text-sm text-gray-600 space-y-2">
                                <div class="flex justify-between">
                                    <span>Status saat ini:</span>
                                    <span class="font-medium {{ $destination->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $destination->is_active ? '✅ Aktif' : '❌ Non-Aktif' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Dibuat:</span>
                                    <span class="font-medium">{{ $destination->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Terakhir diperbarui:</span>
                                    <span class="font-medium">{{ $destination->updated_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">🚀 Aksi</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Save Button -->
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                            💾 Simpan Perubahan
                        </button>

                        <!-- Save & Continue Editing -->
                        <button type="button" 
                                onclick="saveAndContinue()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                            💾➕ Simpan & Lanjut Edit
                        </button>

                        <!-- Preview -->
                        <a href="{{ route('destinations.show', $destination->slug) }}" 
                           target="_blank"
                           class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-3 px-4 rounded-lg text-center block transition-colors">
                            👁️ Preview Website
                        </a>

                        <!-- Cancel -->
                        <a href="{{ route('admin.destinations.show', $destination) }}" 
                           class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg text-center block transition-colors">
                            ❌ Batal
                        </a>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-medium text-blue-900 mb-2">💡 Tips Edit</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Koordinat dapat divalidasi secara real-time</li>
                        <li>• Gambar lama akan diganti jika upload gambar baru</li>
                        <li>• Galeri dapat ditambah atau dihapus terpisah</li>
                        <li>• Slug otomatis dibuat dari nama destinasi</li>
                        <li>• Simpan draft sebagai non-aktif terlebih dahulu</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript untuk Enhanced Edit Form -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugPreview = document.getElementById('slug-preview');
    
    nameInput.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)+/g, '');
        slugPreview.textContent = slug;
    });

    // Character counter for description
    const descriptionTextarea = document.getElementById('description');
    const charCount = document.getElementById('char-count');
    
    descriptionTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    // Coordinate validation
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const coordinateValidation = document.getElementById('coordinate-validation');
    const coordinateMessage = document.getElementById('coordinate-message');
    const googleMapsLink = document.getElementById('google-maps-link');
    
    function validateCoordinates() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        
        if (isNaN(lat) || isNaN(lng)) {
            coordinateValidation.classList.add('hidden');
            return;
        }
        
        // Show validation
        coordinateValidation.classList.remove('hidden');
        
        // Check TTU bounds
        const inTTU = lat >= -10.0 && lat <= -8.5 && lng >= 123.5 && lng <= 125.5;
        
        if (inTTU) {
            coordinateValidation.className = 'bg-green-50 border border-green-200 rounded-lg p-4';
            coordinateMessage.textContent = '✅ Koordinat berada di wilayah ';
        } else {
            coordinateValidation.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-4';
            coordinateMessage.textContent = '⚠️ Koordinat di luar wilayah ';
        }
        
        googleMapsLink.href = `https://www.google.com/maps?q=${lat},${lng}`;
    }
    
    latInput.addEventListener('input', validateCoordinates);
    lngInput.addEventListener('input', validateCoordinates);
    
    // Get current location
    document.getElementById('get-location-btn').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.textContent;
        
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung browser Anda');
            return;
        }
        
        btn.textContent = '🔄 Mendapatkan lokasi...';
        btn.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                latInput.value = position.coords.latitude.toFixed(6);
                lngInput.value = position.coords.longitude.toFixed(6);
                validateCoordinates();
                
                btn.textContent = originalText;
                btn.disabled = false;
            },
            function(error) {
                alert('Gagal mendapatkan lokasi: ' + error.message);
                btn.textContent = originalText;
                btn.disabled = false;
            }
        );
    });

    // Main image upload handling
    const mainImageInput = document.getElementById('main_image');
    const mainImagePreview = document.getElementById('main-image-preview');
    const mainImagePreviewImg = document.getElementById('main-image-preview-img');
    const removeMainImagePreviewBtn = document.getElementById('remove-main-image-preview');
    
    mainImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                mainImagePreviewImg.src = e.target.result;
                mainImagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
    
    removeMainImagePreviewBtn.addEventListener('click', function() {
        mainImageInput.value = '';
        mainImagePreview.classList.add('hidden');
    });

    // Gallery upload handling
    const galleryInput = document.getElementById('gallery');
    const galleryPreview = document.getElementById('gallery-preview');
    const galleryPreviewGrid = document.getElementById('gallery-preview-grid');
    
    galleryInput.addEventListener('change', function() {
        const files = Array.from(this.files);
        
        if (files.length > 0) {
            galleryPreviewGrid.innerHTML = '';
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'gallery-item relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">
                        <button type="button" 
                                onclick="removeNewGalleryImage(${index}, this)"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                            ×
                        </button>
                        <div class="absolute bottom-2 left-2 right-2 bg-black bg-opacity-50 text-white px-1 py-0.5 rounded text-xs truncate">
                            ${file.name}
                        </div>
                    `;
                    galleryPreviewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
            
            galleryPreview.classList.remove('hidden');
        } else {
            galleryPreview.classList.add('hidden');
        }
    });

    // Drag & drop for images
    setupDragAndDrop('main-image-drop-area', 'main_image');
    setupDragAndDrop('gallery-drop-area', 'gallery');
});

// Gallery management functions
function removeFromGallery(filename, button) {
    const item = button.closest('.gallery-item');
    const checkbox = item.querySelector('.remove-gallery-checkbox');
    
    if (item.classList.contains('marked-for-removal')) {
        // Unmark for removal
        item.classList.remove('marked-for-removal');
        item.style.opacity = '1';
        button.textContent = '🗑️ Hapus';
        button.className = button.className.replace('bg-green-500 hover:bg-green-600', 'bg-red-500 hover:bg-red-600');
        checkbox.checked = false;
    } else {
        // Mark for removal
        item.classList.add('marked-for-removal');
        item.style.opacity = '0.5';
        button.textContent = '↩️ Batal';
        button.className = button.className.replace('bg-red-500 hover:bg-red-600', 'bg-green-500 hover:bg-green-600');
        checkbox.checked = true;
    }
    
    updateGalleryRemoveNotice();
}

function updateGalleryRemoveNotice() {
    const checkedBoxes = document.querySelectorAll('.remove-gallery-checkbox:checked');
    const notice = document.getElementById('gallery-remove-notice');
    const countSpan = document.getElementById('gallery-remove-count');
    
    if (checkedBoxes.length > 0) {
        countSpan.textContent = checkedBoxes.length;
        notice.classList.remove('hidden');
    } else {
        notice.classList.add('hidden');
    }
}

function removeNewGalleryImage(index, button) {
    const item = button.closest('.gallery-item');
    item.remove();
    
    // Update file input (complex operation, simplified for demo)
    // In production, you'd need to maintain a separate array of files
}

// Dynamic form fields
function addEntryFee() {
    const container = document.getElementById('entry-fees-container');
    const index = container.children.length;
    
    const div = document.createElement('div');
    div.className = 'entry-fee-item flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" 
               name="entry_fees[${index}][vehicle]" 
               placeholder="Jenis kendaraan" 
               class="form-input flex-1">
        <span class="text-gray-500">Rp</span>
        <input type="number" 
               name="entry_fees[${index}][fee]" 
               placeholder="0" 
               class="form-input w-32">
        <button type="button" 
                onclick="removeEntryFee(this)" 
                class="bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded">
            🗑️
        </button>
    `;
    
    container.appendChild(div);
}

function removeEntryFee(button) {
    button.closest('.entry-fee-item').remove();
}

function addActivity() {
    const container = document.getElementById('activities-container');
    
    const div = document.createElement('div');
    div.className = 'activity-item flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" 
               name="activities[]" 
               placeholder="Nama aktivitas" 
               class="form-input flex-1">
        <button type="button" 
                onclick="removeActivity(this)" 
                class="bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded">
            🗑️
        </button>
    `;
    
    container.appendChild(div);
}

function removeActivity(button) {
    button.closest('.activity-item').remove();
}

// Form submission handling
function saveAndContinue() {
    const form = document.getElementById('edit-form');
    const action = form.action;
    
    // Add parameter to redirect back to edit page
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'continue_editing';
    hiddenInput.value = '1';
    form.appendChild(hiddenInput);
    
    form.submit();
}

// Drag and drop functionality
function setupDragAndDrop(dropAreaId, inputId) {
    const dropArea = document.getElementById(dropAreaId);
    const input = document.getElementById(inputId);
    
    if (!dropArea || !input) return;
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropArea.classList.add('drag-over');
    }

    function unhighlight(e) {
        dropArea.classList.remove('drag-over');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        input.files = files;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        input.dispatchEvent(event);
    }
}
</script>

@endsection