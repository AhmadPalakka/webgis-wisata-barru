<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminDestinationController;

// =============================================
// PUBLIC ROUTES
// =============================================

// Home page
Route::get('/', function () {
    return view('home');
})->name('home');

// Map routes
Route::get('/peta', [MapController::class, 'index'])->name('map.index');
Route::get('/api/destinations', [MapController::class, 'getDestinationsJson'])->name('map.destinations.json');
Route::post('/api/nearest', [MapController::class, 'getNearestDestinations'])->name('map.nearest');

// Destination routes (public)
Route::get('/destinasi', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinasi/{destination}', [DestinationController::class, 'show'])->name('destinations.show');

// =============================================
// ADMIN ROUTES
// =============================================

Route::prefix('admin')->name('admin.')->group(function () {
    
    // === AUTHENTICATION ROUTES (No middleware required) ===
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');
    
    // === PROTECTED ADMIN ROUTES ===
    Route::middleware(['admin'])->group(function () {
        
        // Dashboard
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        
        // === DESTINATIONS CRUD - FULL RESOURCE ROUTES ===
        Route::resource('destinations', AdminDestinationController::class);
        
        // === ADDITIONAL DESTINATION ROUTES ===
        
        // Toggle destination status
        Route::patch('destinations/{destination}/toggle-status', [AdminDestinationController::class, 'toggleStatus'])
            ->name('destinations.toggle-status');
        
        // Export destinations to CSV
        Route::get('destinations-export', [AdminDestinationController::class, 'exportCsv'])
            ->name('destinations.export');
        
        // === DELETE AND RESTORE ROUTES ===
        
        // Bulk delete destinations
        Route::post('destinations/bulk-delete', [AdminDestinationController::class, 'bulkDelete'])
            ->name('destinations.bulk-delete');

        // Trash management routes
        Route::get('destinations/trash/index', [AdminDestinationController::class, 'trashed'])
            ->name('destinations.trashed');

        // Restore single destination from trash
        Route::post('destinations/{id}/restore', [AdminDestinationController::class, 'restore'])
            ->name('destinations.restore');

        // Bulk restore destinations from trash
        Route::post('destinations/bulk-restore', [AdminDestinationController::class, 'bulkRestore'])
            ->name('destinations.bulk-restore');

        // Clean up orphaned images
        Route::post('destinations/cleanup-orphaned-images', [AdminDestinationController::class, 'cleanupOrphanedImages'])
            ->name('destinations.cleanup-images');

        // Force delete (permanent delete from trash)
        Route::delete('destinations/{destination}/force-delete', function(\App\Models\Destination $destination) {
            return app(AdminDestinationController::class)->destroy(
                request()->merge(['force_delete' => 'true']), 
                $destination
            );
        })->name('destinations.force-delete');
        
        // === AJAX/API ROUTES FOR ENHANCED FEATURES ===
        
        // AJAX delete confirmation data
        Route::get('destinations/{destination}/delete-preview', [AdminDestinationController::class, 'getDeletePreview'])
            ->name('destinations.delete-preview');

        // Quick stats for bulk operations
        Route::post('destinations/bulk-preview', [AdminDestinationController::class, 'getBulkPreview'])
            ->name('destinations.bulk-preview');
        
        // API routes for enhanced UPDATE features
        Route::prefix('api')->name('api.')->group(function () {
            // Image preview and management
            Route::post('/preview-image', [AdminDestinationController::class, 'previewImage'])
                ->name('preview-image');
            
            Route::delete('/delete-temp-image', [AdminDestinationController::class, 'deleteTempImage'])
                ->name('delete-temp-image');
            
            // Coordinate validation
            Route::post('/validate-coordinates', [AdminDestinationController::class, 'validateCoordinates'])
                ->name('validate-coordinates');
        });
    });
});