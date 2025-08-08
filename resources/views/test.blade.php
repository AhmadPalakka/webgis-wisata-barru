<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Tailwind CSS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-blue-600 mb-4">WebGIS Barru Setup Test</h1>
        <div class="card p-6">
            <p class="text-gray-700 mb-4">Jika Anda melihat styling yang bagus, berarti Tailwind CSS sudah berfungsi!</p>
            <button class="btn-primary mr-4">Button Primary</button>
            <button class="btn-secondary">Button Secondary</button>
        </div>
    </div>
</body>
</html>