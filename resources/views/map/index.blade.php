@extends('layouts.app')

@section('title', 'Peta Wisata Barru - Portal WebGIS')

@section('content')
<div class="pt-20">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">🗺️ Peta Interaktif Wisata Barru</h1>
            <p class="text-lg text-blue-100 max-w-2xl mx-auto">
                Jelajahi destinasi wisata menarik di Kabupaten Barru. Aktifkan lokasi Anda untuk menemukan destinasi terdekat dan mendapatkan petunjuk arah real-time.
            </p>
        </div>
    </div>

    <!-- Search & Filter Controls - tetap sama dari Tahap 8 -->
    <div class="bg-white border-b border-gray-200 py-6">
        <div class="container mx-auto px-4">
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-6">
                <div class="relative">
                    <input type="text" 
                           id="search-input" 
                           placeholder="🔍 Cari destinasi wisata..." 
                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button onclick="clearSearch()" id="clear-search" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-3 mb-6">
                <button onclick="filterByCategory('all')" 
                        class="category-filter active px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-blue-500 text-white hover:bg-blue-600" 
                        data-category="all">
                    🌟 Semua Kategori
                </button>
                
                @foreach($categories as $key => $category)
                <button onclick="filterByCategory('{{ $key }}')" 
                        class="category-filter px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200" 
                        data-category="{{ $key }}">
                    {{ $category['icon'] }} {{ $category['name'] }}
                </button>
                @endforeach
            </div>
            
            <!-- Stats & Controls -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Menampilkan:</span>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold" id="destination-count">
                        {{ $destinations->count() }}
                    </span>
                    <span class="text-sm text-gray-600">destinasi</span>
                    
                    <!-- Location Status -->
                    <div id="location-status" class="hidden">
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                            📍 Lokasi Aktif
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 flex-wrap">
                    <button onclick="findNearestDestinations()" id="nearest-btn" class="bg-purple-500 hover:bg-purple-600 text-black px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        📍 Destinasi Terdekat
                    </button>
                    <button onclick="resetMapView()" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        🎯 Reset View
                    </button>
                    <button onclick="showAllDestinations()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        📍 Tampilkan Semua
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="relative">
    <div id="map" class="w-full" style="height: 600px; min-height: 600px; background: #f3f4f6;"></div>
        
        <!-- Location Controls -->
        <div class="location-controls">
            <button id="locate-btn" class="location-btn" title="Temukan Lokasi Saya" onclick="getCurrentLocation()">
                📍
            </button>
            
            <button id="tracking-btn" class="location-btn" title="Mode Tracking" onclick="toggleTracking()">
                📡
            </button>
            
            <button id="compass-btn" class="location-btn" title="Orientasi Kompas" onclick="toggleCompass()">
                🧭
            </button>
        </div>
        
        <!-- Loading Overlay -->
        <div id="map-loading" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center">
            <div class="text-center">
                <div class="loading-spinner"></div>
                <p class="text-gray-600 font-medium">Memuat peta wisata Barru...</p>
            </div>
        </div>

        <!-- Location Loading -->
<div id="location-loading" class="absolute inset-0 bg-black bg-opacity-50 items-center justify-center hidden" style="z-index: 2000;">
    <div class="bg-white rounded-lg p-6 text-center">
        <div class="loading-spinner mb-4"></div>
        <p class="text-gray-700 font-medium">Mendapatkan lokasi Anda...</p>
        <p class="text-sm text-gray-500 mt-2">Pastikan GPS aktif dan izinkan akses lokasi</p>
    </div>
</div>
        
        <!-- Location Loading -->
        <div id="location-loading" class="location-loading hidden">
            <div class="loading-spinner"></div>
            <p class="text-gray-700 font-medium">Mendapatkan lokasi Anda...</p>
        </div>
        
        <!-- No Results Message -->
        <div id="no-results" class="absolute inset-0 bg-white bg-opacity-95 flex items-center justify-center hidden">
            <div class="text-center">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak ada destinasi ditemukan</h3>
                <p class="text-gray-600 mb-4">Coba ubah kata kunci pencarian atau filter kategori</p>
                <button onclick="resetFilters()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium">
                    Reset Filter
                </button>
            </div>
        </div>
        
        <!-- Navigation Panel -->
        <div id="navigation-panel" class="navigation-panel">
            <button class="close-btn" onclick="closeNavigation()">×</button>
            <div id="navigation-content">
                <!-- Navigation content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Nearest Destinations Panel - Enhanced Version -->
<div id="nearest-panel" class="bg-white py-12 hidden border-t-4 border-blue-500">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">🎯 Destinasi Terdekat dari Lokasi Anda</h3>
            <p class="text-gray-600" id="nearest-subtitle">Berdasarkan lokasi GPS Anda saat ini</p>
        </div>
        
        <!-- Loading State -->
        <div id="nearest-loading" class="text-center py-8 hidden">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-600">Mencari destinasi terdekat...</p>
        </div>
        
        <!-- Results Container -->
        <div id="nearest-destinations" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Nearest destinations will be loaded here -->
        </div>
        
        <!-- Controls -->
        <div class="text-center mt-8 flex flex-wrap justify-center gap-3">
            <button onclick="hideNearestPanel()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                ❌ Tutup Panel
            </button>
            <button onclick="expandSearchRadius()" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg">
                📍 Perluas Pencarian
            </button>
            <button onclick="refreshNearestDestinations()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                🔄 Refresh
            </button>
        </div>
    </div>
</div>

    <!-- Quick Category Stats -->
    <div class="bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <h3 class="text-xl font-bold text-center mb-6">Kategori Destinasi Wisata</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($categories as $key => $category)
                <div class="bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-shadow cursor-pointer" 
                     onclick="filterByCategory('{{ $key }}')">
                    <div class="text-3xl mb-2">{{ $category['icon'] }}</div>
                    <div class="font-medium text-gray-700">{{ $category['name'] }}</div>
                    <div class="text-sm text-gray-500 mt-1" id="count-{{ $key }}">-</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
// ============= GLOBAL VARIABLES =============
var map;
var markers = [];
var filteredData = [];
var currentCategory = 'all';
var currentSearch = '';
var userLocation = null;
var userLocationMarker = null;
var userAccuracyCircle = null;
var watchId = null;
var trackingMode = false;
var compassMode = false;

// Navigation variables
var routingControl = null;
var currentSearchRadius = 50; // km

// ============= INITIALIZATION =============
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Starting map initialization...');
    initializeMap();
    loadDestinations();
    setupSearchInput();
});

// ============= MAP INITIALIZATION =============
function initializeMap() {
    console.log('📍 Initializing map...');
    
    try {
        // TTU coordinates
        var ttuCenter = [-4.4329581, 119.3670295];
        
        // Create map
        map = L.map('map', {
            center: ttuCenter,
            zoom: 10,
            zoomControl: true,
            scrollWheelZoom: true
        });

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);

        // Position controls
        map.zoomControl.setPosition('topleft');
        
        // Add scale control
        L.control.scale({
            position: 'bottomleft',
            imperial: false
        }).addTo(map);
        
        console.log('✅ Map initialized successfully');
        
    } catch (error) {
        console.error('❌ Map initialization failed:', error);
        alert('Gagal menginisialisasi peta: ' + error.message);
    }
}

// ============= DATA LOADING =============
async function loadDestinations(category = 'all', search = '') {
    console.log('🔄 Loading destinations...');
    
    try {
        const params = new URLSearchParams();
        if (category !== 'all') params.append('category', category);
        if (search) params.append('search', search);
        
        const response = await fetch(`{{ route("map.destinations.json") }}?${params}`);
        const data = await response.json();
        
        filteredData = data;
        console.log(`📍 Found ${filteredData.length} destinations`);
        
        // Add markers to map
        addMarkersToMap();
        
        // Update UI
        updateDestinationCount();
        updateCategoryStats();
        
        // Show/hide no results
        if (filteredData.length === 0) {
            document.getElementById('no-results').classList.remove('hidden');
        } else {
            document.getElementById('no-results').classList.add('hidden');
        }
        
        // Hide loading
        document.getElementById('map-loading').style.display = 'none';
        
    } catch (error) {
        console.error('❌ Error loading destinations:', error);
        document.getElementById('map-loading').innerHTML = 
            '<div class="text-center"><p class="text-red-600">Gagal memuat data: ' + error.message + '</p></div>';
    }
}

// ============= MARKER MANAGEMENT =============
function addMarkersToMap() {
    console.log(`🎯 Adding ${filteredData.length} markers...`);
    
    // Clear existing markers
    markers.forEach(function(marker) {
        map.removeLayer(marker);
    });
    markers = [];
    
    if (filteredData.length === 0) return;
    
    filteredData.forEach(function(destination) {
        if (!destination.latitude || !destination.longitude) return;
        
        // Get color
        const colorMap = {
            'green': '#10b981',
            'blue': '#3b82f6', 
            'yellow': '#f59e0b',
            'purple': '#8b5cf6',
            'indigo': '#6366f1'
        };
        
        const markerColor = colorMap[destination.category_color] || '#3b82f6';
        
        // Create icon
        const customIcon = L.divIcon({
            className: 'custom-marker',
            html: `
                <div class="rounded-full w-10 h-10 flex items-center justify-center text-white font-bold shadow-lg border-2 border-white hover:scale-110 transition-transform cursor-pointer" 
                     style="background-color: ${markerColor}">
                    ${destination.category_icon || '📍'}
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -20]
        });
        
        // Create marker
        const marker = L.marker([destination.latitude, destination.longitude], {
            icon: customIcon,
            title: destination.name
        });
        
        // Create popup content with distance if available
        let distanceInfo = '';
        if (destination.distance) {
            distanceInfo = `<div style="background: #10b981; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-bottom: 8px; display: inline-block;">📏 ${destination.distance} km dari Anda</div>`;
        }
        
        const popupContent = `
            <div style="min-width: 250px;">
                <img src="${destination.image}" alt="${destination.name}" 
                     style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 12px;"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                
                ${distanceInfo}
                
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <span style="font-size: 18px;">${destination.category_icon || '📍'}</span>
                    <span style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-size: 12px;">${destination.category_name || 'Umum'}</span>
                </div>
                
                <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: bold; color: #1e40af;">${destination.name}</h3>
                <p style="margin: 0 0 8px 0; font-size: 14px; color: #374151; line-height: 1.4;">${destination.description}</p>
                
                ${destination.address ? `<p style="font-size: 12px; color: #6b7280; margin-bottom: 12px;"><strong>📍</strong> ${destination.address}</p>` : ''}
                
                <div style="margin-top: 12px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <a href="${destination.url}" target="_blank" 
                       style="display: block; background: #3b82f6; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; text-align: center; font-size: 14px;">
                        👁️ Detail
                    </a>
                    ${userLocation ? `
                    <button onclick="startNavigation(${destination.latitude}, ${destination.longitude}, '${destination.name.replace(/'/g, "\\'")}'); event.stopPropagation();" 
                            style="background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px;">
                        🧭 Navigasi
                    </button>` : ''}
                </div>
            </div>
        `;
        
        marker.bindPopup(popupContent, {
            maxWidth: 320,
            className: 'custom-popup'
        });
        
        // Add to map and array
        marker.addTo(map);
        markers.push(marker);
    });
    
    console.log(`✅ Added ${markers.length} markers`);
    
    // Fit bounds if we have markers
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

// ============= SEARCH AND FILTER FUNCTIONS =============
function setupSearchInput() {
    const searchInput = document.getElementById('search-input');
    const clearButton = document.getElementById('clear-search');
    
    if (!searchInput) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        const value = this.value.trim();
        
        if (clearButton) {
            if (value) {
                clearButton.classList.remove('hidden');
            } else {
                clearButton.classList.add('hidden');
            }
        }
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            currentSearch = value;
            loadDestinations(currentCategory, currentSearch);
        }, 500);
    });
}

function filterByCategory(category) {
    console.log('🔍 Filtering by category: ' + category);
    currentCategory = category;
    
    // Update buttons
    document.querySelectorAll('.category-filter').forEach(function(btn) {
        btn.classList.remove('active', 'bg-blue-500', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    
    const activeBtn = document.querySelector('[data-category="' + category + '"]');
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
        activeBtn.classList.add('active', 'bg-blue-500', 'text-white');
    }
    
    loadDestinations(currentCategory, currentSearch);
}

function clearSearch() {
    const searchInput = document.getElementById('search-input');
    const clearButton = document.getElementById('clear-search');
    
    if (searchInput) searchInput.value = '';
    if (clearButton) clearButton.classList.add('hidden');
    
    currentSearch = '';
    loadDestinations(currentCategory, currentSearch);
}

function resetFilters() {
    clearSearch();
    filterByCategory('all');
    resetMapView();
}

function resetMapView() {
    if (map) {
        map.setView([-4.4329581, 119.3670295], 10);
    }
}

function showAllDestinations() {
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

function updateDestinationCount() {
    const countEl = document.getElementById('destination-count');
    if (countEl) {
        countEl.textContent = filteredData.length;
    }
}

function updateCategoryStats() {
    fetch('{{ route("map.destinations.json") }}')
        .then(function(response) { return response.json(); })
        .then(function(allData) {
            const categoryCounts = {};
            
            allData.forEach(function(dest) {
                categoryCounts[dest.category] = (categoryCounts[dest.category] || 0) + 1;
            });
            
            Object.keys(categoryCounts).forEach(function(category) {
                const countElement = document.getElementById('count-' + category);
                if (countElement) {
                    countElement.textContent = categoryCounts[category] + ' lokasi';
                }
            });
        })
        .catch(function(error) {
            console.error('❌ Error updating stats:', error);
        });
}

// ============= GPS AND LOCATION FUNCTIONS =============
function getCurrentLocation() {
    console.log('📍 Getting location...');
    
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung GPS');
        return;
    }
    
    const locateBtn = document.getElementById('locate-btn');
    if (locateBtn) {
        locateBtn.innerHTML = '🔄';
        locateBtn.classList.add('loading');
    }
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            console.log('✅ Location found:', position.coords);
            
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;
            
            userLocation = { lat: lat, lng: lng, accuracy: accuracy };
            
            // Update marker
            updateUserLocationMarker(lat, lng, accuracy);
            
            // Center map
            map.setView([lat, lng], 14);
            
            // Update button
            if (locateBtn) {
                locateBtn.innerHTML = '📍';
                locateBtn.classList.remove('loading');
                locateBtn.classList.add('active');
            }
            
            // Show status
            const locationStatus = document.getElementById('location-status');
            if (locationStatus) {
                locationStatus.classList.remove('hidden');
            }
            
            alert('✅ Lokasi ditemukan!\nAkurasi: ±' + Math.round(accuracy) + 'm');
        },
        function(error) {
            console.error('❌ Location error:', error);
            
            if (locateBtn) {
                locateBtn.innerHTML = '📍';
                locateBtn.classList.remove('loading');
            }
            
            let message = 'Gagal mendapatkan lokasi: ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message += 'Akses ditolak. Silakan izinkan akses lokasi.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message += 'Lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    message += 'Request timeout.';
                    break;
                default:
                    message += 'Error tidak diketahui.';
                    break;
            }
            alert(message);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        }
    );
}

function updateUserLocationMarker(lat, lng, accuracy) {
    console.log('🎯 Updating user marker');
    
    // Remove existing
    if (userLocationMarker) {
        map.removeLayer(userLocationMarker);
    }
    if (userAccuracyCircle) {
        map.removeLayer(userAccuracyCircle);
    }
    
    // Create icon
    const userIcon = L.divIcon({
        className: 'user-location-marker',
        html: '<div style="width: 20px; height: 20px; background: #3b82f6; border: 3px solid white; border-radius: 50%; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    
    // Add marker
    userLocationMarker = L.marker([lat, lng], {
        icon: userIcon,
        title: 'Lokasi Anda'
    }).addTo(map);
    
    // Add accuracy circle
    userAccuracyCircle = L.circle([lat, lng], {
        radius: accuracy,
        fillColor: '#3b82f6',
        fillOpacity: 0.1,
        color: '#3b82f6',
        weight: 2,
        opacity: 0.6
    }).addTo(map);
    
    // Popup
    userLocationMarker.bindPopup(`
        <div style="text-align: center; padding: 8px;">
            <h3 style="color: #3b82f6; margin: 0 0 8px 0;">📍 Lokasi Anda</h3>
            <p style="margin: 0 0 8px 0; font-size: 14px;">
                ${lat.toFixed(6)}, ${lng.toFixed(6)}<br>
                Akurasi: ±${Math.round(accuracy)}m
            </p>
        </div>
    `);
}

function toggleTracking() {
    console.log('📡 Toggle tracking...');
    
    const trackingBtn = document.getElementById('tracking-btn');
    
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung tracking');
        return;
    }
    
    if (!trackingMode) {
        // Start tracking
        watchId = navigator.geolocation.watchPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                
                userLocation = { lat: lat, lng: lng, accuracy: accuracy };
                updateUserLocationMarker(lat, lng, accuracy);
                
                if (trackingMode) {
                    map.setView([lat, lng], map.getZoom());
                }
            },
            function(error) {
                console.error('Tracking error:', error);
                alert('Tracking error: ' + error.message);
                
                // Stop on error
                if (watchId) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                }
                trackingMode = false;
                
                if (trackingBtn) {
                    trackingBtn.classList.remove('active');
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 5000
            }
        );
        
        trackingMode = true;
        if (trackingBtn) {
            trackingBtn.classList.add('active');
        }
        
        alert('✅ Tracking aktif');
        
    } else {
        // Stop tracking
        if (watchId) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        
        trackingMode = false;
        if (trackingBtn) {
            trackingBtn.classList.remove('active');
        }
        
        alert('ℹ️ Tracking dihentikan');
    }
}

function toggleCompass() {
    console.log('🧭 Toggle compass...');
    
    const compassBtn = document.getElementById('compass-btn');
    
    if (!('DeviceOrientationEvent' in window)) {
        alert('Device tidak mendukung kompas');
        return;
    }
    
    if (!compassMode) {
        // Start compass
        if (typeof DeviceOrientationEvent.requestPermission === 'function') {
            DeviceOrientationEvent.requestPermission()
                .then(function(response) {
                    if (response === 'granted') {
                        startCompass();
                    } else {
                        alert('Izin kompas ditolak');
                    }
                });
        } else {
            startCompass();
        }
        
        function startCompass() {
            window.addEventListener('deviceorientation', handleOrientation);
            compassMode = true;
            if (compassBtn) {
                compassBtn.classList.add('active');
            }
            alert('✅ Kompas aktif');
        }
        
    } else {
        // Stop compass
        window.removeEventListener('deviceorientation', handleOrientation);
        compassMode = false;
        
        if (compassBtn) {
            compassBtn.classList.remove('active');
        }
        
        // Reset rotation
        if (map && map.setBearing) {
            map.setBearing(0);
        }
        
        alert('ℹ️ Kompas dimatikan');
    }
}

function handleOrientation(event) {
    if (event.alpha !== null && compassMode && map) {
        const heading = 360 - event.alpha;
        if (map.setBearing) {
            map.setBearing(heading);
        }
    }
}

// ============= NEAREST DESTINATIONS FUNCTIONS =============
function findNearestDestinations() {
    console.log('🔍 Finding nearest destinations...');
    
    if (!userLocation) {
        alert('⚠️ Lokasi Anda belum dideteksi.\n\nKlik tombol "Temukan Lokasi Saya" 📍 terlebih dahulu untuk mengaktifkan GPS.');
        return;
    }
    
    // Show loading state
    const nearestPanel = document.getElementById('nearest-panel');
    const nearestLoading = document.getElementById('nearest-loading');
    const nearestDestinations = document.getElementById('nearest-destinations');
    
    if (nearestPanel) nearestPanel.classList.remove('hidden');
    if (nearestLoading) nearestLoading.classList.remove('hidden');
    if (nearestDestinations) nearestDestinations.innerHTML = '';
    
    // Scroll to panel
    if (nearestPanel) {
        nearestPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    // Fetch nearest destinations
    fetch('{{ route("map.nearest") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            latitude: userLocation.lat,
            longitude: userLocation.lng,
            radius: currentSearchRadius
        })
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(function(result) {
        console.log('📊 Nearest destinations result:', result);
        
        if (nearestLoading) nearestLoading.classList.add('hidden');
        
        if (result.success && result.data.length > 0) {
            displayNearestDestinations(result.data);
            updateMapWithNearestDestinations(result.data);
            
            // Update subtitle
            const nearestSubtitle = document.getElementById('nearest-subtitle');
            if (nearestSubtitle) {
                nearestSubtitle.textContent = 
                    `${result.total} destinasi ditemukan dalam radius ${result.radius} km`;
            }
                
            alert(`✅ Ditemukan ${result.total} destinasi terdekat!\n\nLihat panel di bawah untuk detail dan navigasi.`);
        } else {
            displayNoNearestResults();
        }
    })
    .catch(function(error) {
        console.error('❌ Error finding nearest destinations:', error);
        if (nearestLoading) nearestLoading.classList.add('hidden');
        alert('❌ Gagal mencari destinasi terdekat.\n\nSilakan coba lagi atau periksa koneksi internet.');
    });
}

function displayNearestDestinations(destinations) {
    const container = document.getElementById('nearest-destinations');
    if (!container) return;
    
    container.innerHTML = destinations.map(function(dest, index) {
        return `
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1" 
                 onclick="focusDestination(${dest.latitude}, ${dest.longitude})">
                <div class="relative">
                    <img src="${dest.image}" alt="${dest.name}" class="w-full h-48 object-cover" 
                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    <div class="absolute top-3 right-3 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                        #${index + 1} Terdekat
                    </div>
                    <div class="absolute bottom-3 left-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded text-sm">
                        📏 ${dest.distance} km
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">${dest.category_icon}</span>
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded font-medium">${dest.category_name}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-blue-600">${dest.distance} km</div>
                            <div class="text-xs text-gray-500">≈ ${dest.travel_time}</div>
                        </div>
                    </div>
                    
                    <h3 class="font-bold text-gray-800 mb-2 text-lg">${dest.name}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">${dest.description}</p>
                    
                    ${dest.address ? `<div class="flex items-start mb-3"><span class="text-gray-400 mr-2 mt-1">📍</span><p class="text-xs text-gray-500 leading-tight">${dest.address}</p></div>` : ''}
                    
                    <div class="grid grid-cols-2 gap-2">
                        <a href="${dest.url}" target="_blank" 
                           onclick="event.stopPropagation()"
                           class="text-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                            👁️ Lihat Detail
                        </a>
                        <button onclick="startNavigation(${dest.latitude}, ${dest.longitude}, '${dest.name.replace(/'/g, "\\'")}'); event.stopPropagation();" 
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm transition-colors">
                            🧭 Navigasi
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function displayNoNearestResults() {
    const container = document.getElementById('nearest-destinations');
    if (!container) return;
    
    container.innerHTML = `
        <div class="col-span-full text-center py-12">
            <div class="text-6xl text-gray-300 mb-4">🏝️</div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada destinasi terdekat</h3>
            <p class="text-gray-500 mb-6">Tidak ditemukan destinasi dalam radius ${currentSearchRadius} km dari lokasi Anda</p>
            <div class="space-x-3">
                <button onclick="expandSearchRadius()" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-medium">
                    🔍 Perluas Pencarian ke ${currentSearchRadius + 25} km
                </button>
                <button onclick="showAllDestinations()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium">
                    🗺️ Lihat Semua Destinasi
                </button>
            </div>
        </div>
    `;
}

function updateMapWithNearestDestinations(destinations) {
    // Update filteredData with nearest destinations
    filteredData = destinations;
    
    // Re-add markers with distance info
    addMarkersToMap();
    
    // Fit map bounds to show user location and nearest destinations
    if (destinations.length > 0) {
        var bounds = L.latLngBounds();
        bounds.extend([userLocation.lat, userLocation.lng]);
        
        destinations.forEach(function(dest) {
            bounds.extend([dest.latitude, dest.longitude]);
        });
        
        map.fitBounds(bounds, { padding: [20, 20] });
    }
}

// ============= NAVIGATION FUNCTIONS =============
function startNavigation(destLat, destLng, destName) {
    console.log(`🧭 Starting navigation to ${destName}`);
    
    if (!userLocation) {
        alert('❌ Lokasi Anda belum dideteksi untuk navigasi.');
        return;
    }
    
    // Check if Leaflet Routing Machine is available
    if (typeof L.Routing === 'undefined') {
        // Fallback to Google Maps
        const googleMapsUrl = `https://www.google.com/maps/dir/${userLocation.lat},${userLocation.lng}/${destLat},${destLng}`;
        window.open(googleMapsUrl, '_blank');
        alert(`🗺️ Membuka Google Maps untuk navigasi ke ${destName}`);
        return;
    }
    
    // Remove existing route
    if (routingControl) {
        map.removeControl(routingControl);
    }
    
    alert(`🗺️ Menghitung rute ke ${destName}...`);
    
    // Create routing control with Leaflet Routing Machine
    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(userLocation.lat, userLocation.lng),
            L.latLng(destLat, destLng)
        ],
        routeWhileDragging: false,
        addWaypoints: false,
        createMarker: function(i, waypoint, n) {
            // Don't create default markers, we have our own
            return null;
        },
        lineOptions: {
            styles: [{ 
                color: '#10b981', 
                weight: 5, 
                opacity: 0.8,
                dashArray: '10, 5'
            }]
        },
        show: false, // Hide instructions panel initially
        draggableWaypoints: false,
        fitSelectedRoutes: true
    }).addTo(map);
    
    // Handle route found event
    routingControl.on('routesfound', function(e) {
        const route = e.routes[0];
        const distance = (route.summary.totalDistance / 1000).toFixed(2);
        const time = Math.round(route.summary.totalTime / 60);
        
        console.log(`✅ Route found: ${distance}km, ${time} minutes`);
        
        showNavigationPanel(destName, distance, time, destLat, destLng);
        
        alert(`✅ Rute ditemukan!\n\n📏 Jarak: ${distance} km\n⏱️ Estimasi: ${time} menit\n\nLihat panel navigasi di bawah peta.`);
    });
    
    // Handle routing error
    routingControl.on('routingerror', function(e) {
        console.error('❌ Routing error:', e);
        alert('❌ Tidak dapat menghitung rute ke destinasi ini.\n\nCoba gunakan Google Maps untuk navigasi alternatif.');
    });
}

function showNavigationPanel(destName, distance, time, destLat, destLng) {
    var panel = document.getElementById('navigation-panel');
    var content = document.getElementById('navigation-content');
    
    if (!panel || !content) {
        console.warn('Navigation panel not found');
        return;
    }
    
    content.innerHTML = `
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                🧭 <span class="ml-2">Navigasi ke ${destName}</span>
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-600 mb-1">${distance} km</div>
                    <div class="text-gray-600">Total Jarak</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600 mb-1">${time} min</div>
                    <div class="text-gray-600">Estimasi Waktu</div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
            <button onclick="zoomToRoute()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                🔍 <span class="ml-2">Lihat Rute</span>
            </button>
            <button onclick="clearRoute()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                ❌ <span class="ml-2">Hapus Rute</span>
            </button>
            <a href="https://www.google.com/maps/dir/${userLocation.lat},${userLocation.lng}/${destLat},${destLng}" 
               target="_blank" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center flex items-center justify-center">
                📱 <span class="ml-2">Google Maps</span>
            </a>
        </div>
        
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-start">
                <span class="text-amber-500 mr-3 mt-1">💡</span>
                <div class="text-sm text-amber-800">
                    <strong>Tips Navigasi:</strong>
                    <ul class="mt-2 space-y-1">
                        <li>• Rute ditampilkan dengan garis hijau putus-putus</li>
                        <li>• Gunakan Google Maps untuk navigasi turn-by-turn yang akurat</li>
                        <li>• Pastikan GPS tetap aktif selama perjalanan</li>
                    </ul>
                </div>
            </div>
        </div>
    `;
    
    panel.classList.add('show');
}

// ============= NAVIGATION CONTROL FUNCTIONS =============
function zoomToRoute() {
    if (routingControl) {
        var bounds = routingControl.getBounds();
        if (bounds && bounds.isValid()) {
            map.fitBounds(bounds, { padding: [20, 20] });
        }
    }
}

function clearRoute() {
    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
    }
    closeNavigation();
    alert('✅ Rute navigasi telah dihapus dari peta.');
}

function closeNavigation() {
    var panel = document.getElementById('navigation-panel');
    if (panel) {
        panel.classList.remove('show');
    }
}

// ============= ADDITIONAL HELPER FUNCTIONS =============
function expandSearchRadius() {
    currentSearchRadius += 25; // Increase by 25km
    if (currentSearchRadius > 200) {
        currentSearchRadius = 200; // Max 200km
    }
    
    console.log(`🔍 Expanding search radius to ${currentSearchRadius}km`);
    findNearestDestinations();
}

function refreshNearestDestinations() {
    console.log('🔄 Refreshing nearest destinations...');
    findNearestDestinations();
}

function hideNearestPanel() {
    const nearestPanel = document.getElementById('nearest-panel');
    if (nearestPanel) {
        nearestPanel.classList.add('hidden');
    }
    // Reset search radius
    currentSearchRadius = 50;
    // Reset to show all destinations
    loadDestinations(currentCategory, currentSearch);
}

function focusDestination(lat, lng) {
    map.setView([lat, lng], 16);
    
    // Find and open popup for this destination
    markers.forEach(function(marker) {
        var markerLatLng = marker.getLatLng();
        if (Math.abs(markerLatLng.lat - lat) < 0.001 && Math.abs(markerLatLng.lng - lng) < 0.001) {
            marker.openPopup();
        }
    });
}

// ============= CSS STYLES =============
const style = document.createElement('style');
style.textContent = `
    #map {
        height: 600px !important;
        width: 100% !important;
        min-height: 600px !important;
    }
    
    .custom-marker {
        background: transparent !important;
        border: none !important;
    }
    
    .user-location-marker {
        background: transparent !important;
        border: none !important;
    }
    
    .location-btn.loading {
        opacity: 0.6;
        animation: pulse 1s infinite;
    }
    
    .location-btn.active {
        background: #3b82f6 !important;
        color: white !important;
    }
    
    .custom-popup .leaflet-popup-content-wrapper {
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .custom-popup .leaflet-popup-tip {
        background: white !important;
    }
    
    /* Navigation panel styles */
    #navigation-panel {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #e5e7eb;
        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        z-index: 1000;
        max-height: 50vh;
        overflow-y: auto;
    }
    
    #navigation-panel.show {
        transform: translateY(0);
    }
    
    /* Nearest destinations panel */
    #nearest-panel {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f9fafb;
        margin-top: 2rem;
        overflow: hidden;
    }
    
    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Responsive grid for nearest destinations */
    #nearest-destinations {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem;
    }
    
    @media (max-width: 768px) {
        #nearest-destinations {
            grid-template-columns: 1fr;
            padding: 1rem;
            gap: 1rem;
        }
        
        #navigation-panel {
            max-height: 60vh;
        }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    /* Loading animation */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-radius: 50%;
        border-top: 3px solid #3498db;
        animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Route line styles */
    .leaflet-routing-container-hide {
        display: none;
    }
    
    /* Enhanced button styles */
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }
    
    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
    }
`;
document.head.appendChild(style);

console.log('🎉 Enhanced GPS Navigation functions loaded successfully');
</script>