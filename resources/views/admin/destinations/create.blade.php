@extends('admin.layouts.app')

@section('title', 'Tambah Destinasi Baru')
@section('page-title', 'Tambah Destinasi Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Form Tambah Destinasi</h2>
        
        <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Nama Destinasi -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Destinasi *</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Pantai Tanjung Bastian">
                </div>
                
                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                    <select id="category" name="category" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $key => $category)
                            <option value="{{ $key }}">{{ $category['icon'] }} {{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                    <textarea id="description" name="description" required rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Deskripsikan destinasi wisata ini..."></textarea>
                </div>
                
                <!-- Koordinat -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude *</label>
                        <input type="number" id="latitude" name="latitude" step="any" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="-9.17108">
                    </div>
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude *</label>
                        <input type="number" id="longitude" name="longitude" step="any" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="124.55241">
                    </div>
                </div>
                
                <!-- Gambar Utama -->
                <div>
                    <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama *</label>
                    <input type="file" id="main_image" name="main_image" required accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Status -->
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" checked
                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">
                            Aktifkan destinasi ini (akan tampil di website dan peta)
                        </label>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.destinations.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        Simpan Destinasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection