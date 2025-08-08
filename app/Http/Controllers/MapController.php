<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Display map view with all destinations and categories.
     */
    public function index()
    {
        $destinations = Destination::where('is_active', true)->get();
        $categories = Destination::getCategories();
        
        return view('map.index', compact('destinations', 'categories'));
    }

    /**
     * Get filtered destinations as JSON (based on category or search).
     */
    public function getDestinationsJson(Request $request)
    {
        $query = Destination::where('is_active', true);
        
        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        // Search by name, description, or address
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }
        
        $destinations = $query->select('id', 'name', 'slug', 'category', 'description', 'address', 'latitude', 'longitude', 'main_image')
            ->get()
            ->map(function ($destination) {
                $categoryInfo = $destination->getCategoryInfo();
                
                return [
                    'id' => $destination->id,
                    'name' => $destination->name,
                    'slug' => $destination->slug,
                    'category' => $destination->category,
                    'category_name' => $categoryInfo['name'],
                    'category_icon' => $categoryInfo['icon'],
                    'category_color' => $categoryInfo['color'],
                    'description' => \Str::limit($destination->description, 100),
                    'address' => $destination->address,
                    'latitude' => (float) $destination->latitude,
                    'longitude' => (float) $destination->longitude,
                    'image' => asset('images/destinations/' . $destination->main_image),
                    'url' => route('destinations.show', $destination->slug)
                ];
            });
        
        return response()->json($destinations);
    }

    /**
     * Get nearest destinations from user location.
     */
    public function getNearestDestinations(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'integer|min:1|max:100'
        ]);
        
        $userLat = $request->latitude;
        $userLng = $request->longitude;
        $radius = $request->radius ?? 50; // Default 50km
        
        $destinations = Destination::where('is_active', true)
            ->get()
            ->map(function ($destination) use ($userLat, $userLng) {
                $distance = $this->calculateDistance(
                    $userLat, $userLng,
                    $destination->latitude, $destination->longitude
                );
                
                $categoryInfo = $destination->getCategoryInfo();
                
                return [
                    'id' => $destination->id,
                    'name' => $destination->name,
                    'slug' => $destination->slug,
                    'category' => $destination->category,
                    'category_name' => $categoryInfo['name'],
                    'category_icon' => $categoryInfo['icon'],
                    'category_color' => $categoryInfo['color'],
                    'description' => \Str::limit($destination->description, 100),
                    'address' => $destination->address,
                    'latitude' => (float) $destination->latitude,
                    'longitude' => (float) $destination->longitude,
                    'distance' => round($distance, 2),
                    'travel_time' => $this->estimateTravelTime($distance),
                    'image' => asset('images/destinations/' . $destination->main_image),
                    'url' => route('destinations.show', $destination->slug)
                ];
            })
            ->filter(function ($destination) use ($radius) {
                return $destination['distance'] <= $radius;
            })
            ->sortBy('distance')
            ->take(10)
            ->values();
        
        return response()->json([
            'success' => true,
            'data' => $destinations,
            'total' => $destinations->count(),
            'radius' => $radius,
            'user_location' => [
                'latitude' => $userLat,
                'longitude' => $userLng
            ]
        ]);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula.
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }

    /**
     * Estimate travel time based on distance (in km).
     */
    private function estimateTravelTime($distance)
    {
        $averageSpeed = 30; // km/h
        $timeInHours = $distance / $averageSpeed;
        $timeInMinutes = round($timeInHours * 60);
        
        if ($timeInMinutes < 60) {
            return $timeInMinutes . ' menit';
        } else {
            $hours = floor($timeInMinutes / 60);
            $minutes = $timeInMinutes % 60;
            return $minutes > 0
                ? $hours . ' jam ' . $minutes . ' menit'
                : $hours . ' jam';
        }
    }
}
