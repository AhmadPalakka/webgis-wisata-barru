<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal WebGIS Wisata Barru')</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    
    <!-- Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Enhanced Map & Location Styles -->
    <style>
        /* Previous popup styles */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .leaflet-popup-content {
            margin: 0;
            line-height: 1.4;
        }
        .destination-popup {
            min-width: 280px;
        }
        .destination-popup img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .destination-popup h3 {
            margin: 0 0 8px 0;
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        .destination-popup p {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #374151;
            line-height: 1.4;
        }
        
        /* Location Controls */
        .location-controls {
            position: absolute;
            top: 70px;
            right: 12px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .location-btn {
            background: white;
            border: 2px solid rgba(0,0,0,0.2);
            border-radius: 8px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
            font-size: 18px;
        }
        
        .location-btn:hover {
            background: #f3f4f6;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .location-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .location-btn.loading {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* User Location Marker */
        .user-location-marker {
            width: 20px;
            height: 20px;
            background: #3b82f6;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
            animation: locationPulse 2s infinite;
        }
        
        @keyframes locationPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
        
        /* Distance Info */
        .distance-info {
            background: rgba(59, 130, 246, 0.9);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin: 8px 0;
            display: inline-block;
        }
        
        /* Navigation Panel */
        .navigation-panel {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            padding: 20px;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .navigation-panel.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .navigation-panel .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            color: #6b7280;
            cursor: pointer;
        }
        
        /* Enhanced Button Styles */
        .btn-location {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        
        .btn-location:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .location-controls {
                top: 80px;
                right: 8px;
            }
            
            .location-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .navigation-panel {
                left: 10px;
                right: 10px;
                bottom: 10px;
                padding: 15px;
            }
            
            .destination-popup {
                min-width: 240px;
            }
        }
        
        /* Notification Toast */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-left: 4px solid #3b82f6;
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
        }
        
        .notification-toast.show {
            transform: translateX(0);
        }
        
        .notification-toast.success {
            border-left-color: #10b981;
        }
        
        .notification-toast.error {
            border-left-color: #ef4444;
        }
        
        /* Loading overlay for location */
        .location-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255,255,255,0.95);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            text-align: center;
            z-index: 2000;
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e5e7eb;
            border-left-color: #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 12px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    @include('layouts.navigation')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.footer')
    
    <!-- Notification Toast Template -->
    <div id="notification-toast" class="notification-toast">
        <div class="flex items-start">
            <div class="flex-shrink-0 mr-3">
                <span id="toast-icon">ℹ️</span>
            </div>
            <div class="flex-grow">
                <h4 id="toast-title" class="font-semibold text-gray-900 mb-1"></h4>
                <p id="toast-message" class="text-sm text-gray-600"></p>
            </div>
            <button onclick="hideNotification()" class="flex-shrink-0 ml-3 text-gray-400 hover:text-gray-600">
                <span class="text-xl">×</span>
            </button>
        </div>
    </div>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    
    <!-- Leaflet Routing Machine -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    
    <!-- Global notification functions -->
    <script>
        function showNotification(title, message, type = 'info') {
            const toast = document.getElementById('notification-toast');
            const icon = document.getElementById('toast-icon');
            const titleEl = document.getElementById('toast-title');
            const messageEl = document.getElementById('toast-message');
            
            // Set content
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            // Set icon and style based on type
            toast.className = 'notification-toast';
            if (type === 'success') {
                toast.classList.add('success');
                icon.textContent = '✅';
            } else if (type === 'error') {
                toast.classList.add('error');
                icon.textContent = '❌';
            } else {
                icon.textContent = 'ℹ️';
            }
            
            // Show toast
            toast.classList.add('show');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                hideNotification();
            }, 5000);
        }
        
        function hideNotification() {
            const toast = document.getElementById('notification-toast');
            toast.classList.remove('show');
        }
    </script>
</body>
</html>