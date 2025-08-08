<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin - WebGIS Barru</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Admin Panel</h1>
            <p class="text-gray-600">Login untuk mengelola destinasi wisata Barru</p>
        </div>
        
        <!-- Alert Messages -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            
            <div class="form-group">
                <label for="password" class="form-label">Password Admin</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input @error('password') error @enderror"
                    placeholder="Masukkan password admin"
                    required
                    autocomplete="current-password"
                >
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="w-full btn-admin-primary py-3 text-lg">
                Login ke Admin Panel
            </button>
        </form>
        
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                ← Kembali ke Website Utama
            </a>
        </div>
        
        <!-- Demo Info -->
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded">
            <div class="text-center">
                <p class="text-xs text-yellow-700">
                    <strong>Demo Password:</strong> admin123
                </p>
            </div>
        </div>
    </div>
</body>
</html>