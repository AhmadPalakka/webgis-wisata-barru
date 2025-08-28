<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class AdminDestinationController extends Controller
{
    // =============================================
    // CRUD METHODS
    // =============================================

    /**
     * Display a listing of destinations with filtering and search
     */
    public function index(Request $request)
    {
        $query = Destination::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');
        
        $allowedSorts = ['name', 'category', 'created_at', 'updated_at', 'is_active'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }
        
        $destinations = $query->paginate(10)->appends($request->all());
        
        // Statistics
        $stats = [
            'total' => Destination::count(),
            'active' => Destination::where('is_active', true)->count(),
            'inactive' => Destination::where('is_active', false)->count(),
            'categories' => Destination::select('category')
                ->groupBy('category')
                ->selectRaw('count(*) as count, category')
                ->pluck('count', 'category')
                ->toArray()
        ];
        
        $categories = Destination::getCategories();
        
        return view('admin.destinations.index', compact(
            'destinations', 
            'categories', 
            'stats',
            'request'
        ));
    }

    /**
     * Show the form for creating a new destination
     */
    public function create()
    {
        $categories = Destination::getCategories();
        return view('admin.destinations.create', compact('categories'));
    }

    /**
     * Store a newly created destination
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', array_keys(Destination::getCategories())),
            'description' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'main_image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'address' => 'nullable|string',
            'contact' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Handle file upload
        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $mainImageName = Str::slug($request->name) . '-main-' . time() . '.' . $mainImage->getClientOriginalExtension();
            
            $uploadPath = public_path('images/destinations');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $mainImage->move($uploadPath, $mainImageName);
            $mainImagePath = $mainImageName;
        }

        Destination::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'contact' => $request->contact,
            'main_image' => $mainImagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi wisata berhasil ditambahkan!');
    }

    /**
     * Display the specified destination
     */
    public function show(Destination $destination)
    {
        $relatedDestinations = Destination::where('category', $destination->category)
            ->where('id', '!=', $destination->id)
            ->where('is_active', true)
            ->limit(3)
            ->get();
        
        $stats = [
            'category_count' => Destination::where('category', $destination->category)->count(),
            'same_area_count' => Destination::whereBetween('latitude', [
                $destination->latitude - 0.1, 
                $destination->latitude + 0.1
            ])->whereBetween('longitude', [
                $destination->longitude - 0.1, 
                $destination->longitude + 0.1
            ])->where('id', '!=', $destination->id)->count(),
        ];
        
        return view('admin.destinations.show', compact(
            'destination', 
            'relatedDestinations',
            'stats'
        ));
    }

    /**
     * Show the form for editing the specified destination
     */
    public function edit(Destination $destination)
    {
        $categories = Destination::getCategories();
        
        // Check if main image exists
        $imageExists = File::exists(public_path('images/destinations/' . $destination->main_image));
        
        // Get existing gallery images
        $galleryImages = [];
        if ($destination->gallery) {
            foreach ($destination->gallery as $image) {
                $galleryImages[] = [
                    'filename' => $image,
                    'exists' => File::exists(public_path('images/destinations/' . $image)),
                    'url' => asset('images/destinations/' . $image)
                ];
            }
        }
        
        return view('admin.destinations.edit', compact(
            'destination', 
            'categories',
            'imageExists',
            'galleryImages'
        ));
    }

    /**
     * Update the specified destination
     */
    public function update(Request $request, Destination $destination)
    {
        // Validation with conditional rules
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('destinations')->ignore($destination->id)
            ],
            'category' => 'required|in:' . implode(',', array_keys(Destination::getCategories())),
            'description' => 'required|string',
            'address' => 'nullable|string|max:500',
            'contact' => 'nullable|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'main_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'entry_fees' => 'nullable|array',
            'entry_fees.*.vehicle' => 'required_with:entry_fees|string',
            'entry_fees.*.fee' => 'required_with:entry_fees|numeric|min:0',
            'activities' => 'nullable|array',
            'activities.*' => 'string|max:100',
            'operating_hours' => 'nullable|string|max:100',
            'visitor_info' => 'nullable|string',
            'is_active' => 'boolean',
            'remove_gallery' => 'nullable|array',
            'remove_gallery.*' => 'string'
        ];

        $request->validate($rules);

        // Prepare update data
        $updateData = [
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'address' => $request->address,
            'contact' => $request->contact,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'operating_hours' => $request->operating_hours ?? '24 jam',
            'visitor_info' => $request->visitor_info,
            'is_active' => $request->has('is_active'),
        ];

        // Handle entry fees
        if ($request->has('entry_fees') && is_array($request->entry_fees)) {
            $entryFees = [];
            foreach ($request->entry_fees as $fee) {
                if (!empty($fee['vehicle']) && !empty($fee['fee'])) {
                    $entryFees[$fee['vehicle']] = (int) $fee['fee'];
                }
            }
            $updateData['entry_fees'] = !empty($entryFees) ? $entryFees : null;
        } else {
            $updateData['entry_fees'] = null;
        }

        // Handle activities
        if ($request->has('activities')) {
            $activities = array_filter($request->activities, function($activity) {
                return !empty(trim($activity));
            });
            $updateData['activities'] = !empty($activities) ? array_values($activities) : null;
        } else {
            $updateData['activities'] = null;
        }

        // Handle main image update
        if ($request->hasFile('main_image')) {
            // Delete old main image if exists
            if ($destination->main_image) {
                $oldImagePath = public_path('images/destinations/' . $destination->main_image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            // Upload new main image
            $mainImage = $request->file('main_image');
            $mainImageName = Str::slug($request->name) . '-main-' . time() . '.' . $mainImage->getClientOriginalExtension();
            
            $uploadPath = public_path('images/destinations');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $mainImage->move($uploadPath, $mainImageName);
            $updateData['main_image'] = $mainImageName;
        }

        // Handle gallery images
        $currentGallery = $destination->gallery ?? [];
        
        // Remove selected images from gallery
        if ($request->has('remove_gallery') && is_array($request->remove_gallery)) {
            foreach ($request->remove_gallery as $imageToRemove) {
                // Remove from filesystem
                $imagePath = public_path('images/destinations/' . $imageToRemove);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
                
                // Remove from gallery array
                $currentGallery = array_values(array_filter($currentGallery, function($img) use ($imageToRemove) {
                    return $img !== $imageToRemove;
                }));
            }
        }

        // Add new gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $galleryImage) {
                if ($galleryImage->isValid()) {
                    $galleryImageName = Str::slug($request->name) . '-gallery-' . time() . '-' . $index . '.' . $galleryImage->getClientOriginalExtension();
                    
                    $uploadPath = public_path('images/destinations');
                    $galleryImage->move($uploadPath, $galleryImageName);
                    
                    $currentGallery[] = $galleryImageName;
                }
            }
        }

        $updateData['gallery'] = !empty($currentGallery) ? $currentGallery : null;

        // Update slug if name changed
        if ($destination->name !== $request->name) {
            $updateData['slug'] = Str::slug($request->name);
        }

        // Update the destination
        $destination->update($updateData);

        return redirect()->route('admin.destinations.show', $destination)
            ->with('success', 'Destinasi wisata berhasil diperbarui!');
    }

    /**
     * Delete single destination with soft delete support
     */
    public function destroy(Request $request, Destination $destination)
    {
        try {
            // Log the deletion attempt
            \Log::info('Admin attempting to delete destination', [
                'destination_id' => $destination->id,
                'destination_name' => $destination->name,
                'admin_session' => session('admin_user', 'unknown')
            ]);

            // Store destination data for potential restore
            $destinationData = $destination->toArray();
            
            // Handle image cleanup
            $this->cleanupDestinationImages($destination);
            
            // Perform soft delete (or hard delete based on request)
            if ($request->has('force_delete') && $request->force_delete == 'true') {
                // Hard delete - permanently remove
                $destination->forceDelete();
                $message = 'Destinasi berhasil dihapus secara permanen!';
            } else {
                // Soft delete - can be restored
                $destination->delete();
                $message = 'Destinasi berhasil Dihapus.';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route('admin.destinations.index')
                ->with('success', $message)
                ->with('deleted_destination', $destinationData); // For undo functionality

        } catch (\Exception $e) {
            \Log::error('Failed to delete destination', [
                'destination_id' => $destination->id,
                'error' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus destinasi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus destinasi: ' . $e->getMessage());
        }
    }

    // =============================================
    // BULK OPERATIONS
    // =============================================

    /**
     * Bulk delete multiple destinations
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'destination_ids' => 'required|array|min:1',
            'destination_ids.*' => 'exists:destinations,id',
            'action' => 'required|in:soft_delete,force_delete'
        ]);

        try {
            $destinationIds = $request->destination_ids;
            $action = $request->action;
            
            // Get destinations before deletion for logging
            $destinations = Destination::whereIn('id', $destinationIds)->get();
            
            $deletedCount = 0;
            $errors = [];

            foreach ($destinations as $destination) {
                try {
                    // Cleanup images
                    $this->cleanupDestinationImages($destination);
                    
                    // Perform deletion based on action
                    if ($action === 'force_delete') {
                        $destination->forceDelete();
                    } else {
                        $destination->delete();
                    }
                    
                    $deletedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Gagal menghapus {$destination->name}: " . $e->getMessage();
                    \Log::error('Bulk delete failed for destination', [
                        'destination_id' => $destination->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Log bulk deletion
            \Log::info('Bulk deletion completed', [
                'total_requested' => count($destinationIds),
                'successfully_deleted' => $deletedCount,
                'errors' => count($errors),
                'action' => $action
            ]);

            $message = "Berhasil menghapus {$deletedCount} destinasi.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " destinasi gagal dihapus.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'deleted_count' => $deletedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            \Log::error('Bulk delete operation failed', [
                'error' => $e->getMessage(),
                'destination_ids' => $request->destination_ids
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan bulk delete: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk restore trashed destinations
     */
    public function bulkRestore(Request $request)
    {
        $request->validate([
            'destination_ids' => 'required|array|min:1',
            'destination_ids.*' => 'exists:destinations,id'
        ]);

        try {
            $restoredCount = 0;
            $destinations = Destination::withTrashed()->whereIn('id', $request->destination_ids)->get();

            foreach ($destinations as $destination) {
                if ($destination->trashed()) {
                    $destination->restore();
                    $restoredCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil memulihkan {$restoredCount} destinasi.",
                'restored_count' => $restoredCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan bulk restore: ' . $e->getMessage()
            ], 500);
        }
    }

    // =============================================
    // AJAX/API METHODS
    // =============================================

    /**
     * Get destination preview data for delete confirmation
     */
    public function getDeletePreview(Destination $destination)
    {
        try {
            $categoryInfo = $destination->getCategoryInfo();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $destination->id,
                    'name' => $destination->name,
                    'category' => [
                        'name' => $categoryInfo['name'],
                        'icon' => $categoryInfo['icon'],
                        'color' => $categoryInfo['color']
                    ],
                    'main_image_url' => $destination->main_image ? 
                        asset('images/destinations/' . $destination->main_image) : 
                        asset('images/placeholder.jpg'),
                    'created_at' => $destination->created_at->format('d M Y'),
                    'gallery_count' => $destination->gallery ? count($destination->gallery) : 0,
                    'is_active' => $destination->is_active,
                    'address' => $destination->address,
                    'description' => \Str::limit($destination->description, 100)
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get destination preview', [
                'destination_id' => $destination->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat preview destinasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bulk preview data for bulk operations
     */
    public function getBulkPreview(Request $request)
    {
        try {
            $request->validate([
                'destination_ids' => 'required|array|min:1',
                'destination_ids.*' => 'exists:destinations,id'
            ]);

            $destinations = Destination::whereIn('id', $request->destination_ids)
                ->select('id', 'name', 'category', 'main_image', 'is_active')
                ->get()
                ->map(function($dest) {
                    $categoryInfo = $dest->getCategoryInfo();
                    
                    return [
                        'id' => $dest->id,
                        'name' => $dest->name,
                        'category' => [
                            'name' => $categoryInfo['name'],
                            'icon' => $categoryInfo['icon'],
                            'color' => $categoryInfo['color']
                        ],
                        'main_image_url' => $dest->main_image ? 
                            asset('images/destinations/' . $dest->main_image) : 
                            asset('images/placeholder.jpg'),
                        'is_active' => $dest->is_active
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'destinations' => $destinations,
                    'count' => $destinations->count()
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get bulk preview', [
                'error' => $e->getMessage(),
                'destination_ids' => $request->destination_ids ?? []
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat preview bulk operation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview image before upload
     */
    public function previewImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'preview-' . time() . '.' . $image->getClientOriginalExtension();
            
            $uploadPath = public_path('images/temp');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            
            return response()->json([
                'success' => true,
                'image_url' => asset('images/temp/' . $imageName),
                'image_name' => $imageName
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupload gambar'
        ]);
    }

    /**
     * Delete temporary image
     */
    public function deleteTempImage(Request $request)
    {
        $imageName = $request->input('image_name');
        $imagePath = public_path('images/temp/' . $imageName);
        
        if (File::exists($imagePath)) {
            File::delete($imagePath);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false]);
    }

    /**
     * Validate coordinates in real-time
     */
    public function validateCoordinates(Request $request)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        
        // Basic validation
        if (!is_numeric($lat) || !is_numeric($lng)) {
            return response()->json([
                'valid' => false,
                'message' => 'Koordinat harus berupa angka'
            ]);
        }
        
        if ($lat < -90 || $lat > 90) {
            return response()->json([
                'valid' => false,
                'message' => 'Latitude harus antara -90 dan 90'
            ]);
        }
        
        if ($lng < -180 || $lng > 180) {
            return response()->json([
                'valid' => false,
                'message' => 'Longitude harus antara -180 dan 180'
            ]);
        }
        
        // Check if coordinates are in TTU area (approximate bounds)
        $ttuBounds = [
            'lat_min' => -10.0,
            'lat_max' => -8.5,
            'lng_min' => 123.5,
            'lng_max' => 125.5
        ];
        
        $inTTU = $lat >= $ttuBounds['lat_min'] && $lat <= $ttuBounds['lat_max'] &&
                 $lng >= $ttuBounds['lng_min'] && $lng <= $ttuBounds['lng_max'];
        
        return response()->json([
            'valid' => true,
            'in_ttu' => $inTTU,
            'message' => $inTTU ? 'Koordinat berada di wilayah Barru' : 'Koordinat di luar wilayah Barru',
            'google_maps_url' => "https://www.google.com/maps?q={$lat},{$lng}"
        ]);
    }

    // =============================================
    // TRASH MANAGEMENT
    // =============================================

    /**
     * Show trashed destinations
     */
    public function trashed(Request $request)
    {
        $query = Destination::onlyTrashed();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Date range filter
        if ($request->has('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('deleted_at', today());
                    break;
                case 'week':
                    $query->where('deleted_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('deleted_at', '>=', now()->subMonth());
                    break;
                case 'expiring':
                    $query->where('deleted_at', '<', now()->subDays(23));
                    break;
            }
        }

        $destinations = $query->orderBy('deleted_at', 'desc')->paginate(10);
        $categories = Destination::getCategories();

        return view('admin.destinations.trashed', compact('destinations', 'categories'));
    }

    /**
     * Restore soft deleted destination
     */
    public function restore($id)
    {
        try {
            $destination = Destination::withTrashed()->findOrFail($id);
            
            if (!$destination->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destinasi tidak dalam status terhapus'
                ], 400);
            }

            $destination->restore();

            \Log::info('Destination restored', [
                'destination_id' => $destination->id,
                'destination_name' => $destination->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Destinasi berhasil dipulihkan!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to restore destination', [
                'destination_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan destinasi: ' . $e->getMessage()
            ], 500);
        }
    }

    // =============================================
    // UTILITY METHODS
    // =============================================

    /**
     * Toggle destination status (active/inactive)
     */
    public function toggleStatus(Request $request, Destination $destination)
    {
        try {
            if ($request->expectsJson()) {
                $request->validate([
                    'is_active' => 'required|boolean'
                ]);
                $destination->is_active = $request->is_active;
            } else {
                // For non-AJAX requests, toggle the current status
                $destination->is_active = !$destination->is_active;
            }

            $destination->save();

            $status = $destination->is_active ? 'diaktifkan' : 'dinonaktifkan';
            $message = "Destinasi berhasil {$status}!";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah status destinasi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengubah status destinasi: ' . $e->getMessage());
        }
    }

    /**
     * Export destinations to CSV
     */
    public function exportCsv(Request $request)
    {
        $destinations = Destination::all();
        
        $csvData = [];
        $csvData[] = [
            'ID', 'Nama', 'Kategori', 'Deskripsi', 'Latitude', 'Longitude', 
            'Alamat', 'Status', 'Dibuat', 'Diperbarui'
        ];
        
        foreach ($destinations as $dest) {
            $categoryInfo = $dest->getCategoryInfo();
            $csvData[] = [
                $dest->id,
                $dest->name,
                $categoryInfo['name'],
                strip_tags($dest->description),
                $dest->latitude,
                $dest->longitude,
                $dest->address ?? 'N/A',
                $dest->is_active ? 'Aktif' : 'Non-Aktif',
                $dest->created_at->format('Y-m-d H:i:s'),
                $dest->updated_at->format('Y-m-d H:i:s'),
            ];
        }
        
        $filename = 'destinations_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clean up orphaned images
     */
    public function cleanupOrphanedImages()
    {
        try {
            $imageDirectory = public_path('images/destinations/');
            
            if (!\File::isDirectory($imageDirectory)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image directory tidak ditemukan'
                ]);
            }

            // Get all image files
            $allFiles = \File::files($imageDirectory);
            $allImageNames = array_map(function($file) {
                return $file->getFilename();
            }, $allFiles);

            // Get all used images from database
            $usedImages = [];
            
            // Main images
            $mainImages = Destination::whereNotNull('main_image')->pluck('main_image')->toArray();
            $usedImages = array_merge($usedImages, $mainImages);
            
            // Gallery images
            $destinations = Destination::whereNotNull('gallery')->get();
            foreach ($destinations as $destination) {
                if (is_array($destination->gallery)) {
                    $usedImages = array_merge($usedImages, $destination->gallery);
                }
            }

            // Find orphaned images
            $orphanedImages = array_diff($allImageNames, $usedImages);
            
            $deletedCount = 0;
            foreach ($orphanedImages as $orphanedImage) {
                $imagePath = $imageDirectory . $orphanedImage;
                if (\File::exists($imagePath)) {
                    \File::delete($imagePath);
                    $deletedCount++;
                }
            }

            \Log::info('Orphaned images cleanup completed', [
                'total_files' => count($allImageNames),
                'used_images' => count($usedImages),
                'orphaned_deleted' => $deletedCount
            ]);

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} orphaned images.",
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Orphaned images cleanup failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan orphaned images: ' . $e->getMessage()
            ], 500);
        }
    }

    // =============================================
    // PRIVATE HELPER METHODS
    // =============================================

    /**
     * Cleanup destination images from filesystem
     */
    private function cleanupDestinationImages(Destination $destination)
    {
        try {
            $imagesToDelete = [];

            // Add main image
            if ($destination->main_image) {
                $imagesToDelete[] = $destination->main_image;
            }

            // Add gallery images
            if ($destination->gallery && is_array($destination->gallery)) {
                $imagesToDelete = array_merge($imagesToDelete, $destination->gallery);
            }

            // Delete images from filesystem
            foreach ($imagesToDelete as $image) {
                $imagePath = public_path('images/destinations/' . $image);
                if (\File::exists($imagePath)) {
                    \File::delete($imagePath);
                    \Log::info('Deleted image file', ['path' => $imagePath]);
                }
            }

            return true;
        } catch (\Exception $e) {
            \Log::warning('Failed to cleanup some images', [
                'destination_id' => $destination->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}