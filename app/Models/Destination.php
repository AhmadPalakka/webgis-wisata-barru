<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Destination extends Model
{
    use SoftDeletes; // Enable soft delete functionality

    protected $fillable = [
        'name', 'slug', 'category', 'description', 'address', 'contact',
        'latitude', 'longitude', 'main_image', 'gallery', 'entry_fees', 
        'operating_hours', 'visitor_info', 'activities', 'is_active'
    ];

    protected $casts = [
        'gallery' => 'array',
        'entry_fees' => 'array',
        'activities' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected $dates = ['deleted_at']; // Specify soft delete date field

    // Kategori yang tersedia
    public static function getCategories()
    {
        return [
            'wisata-alam' => [
                'name' => 'Wisata Alam',
                'icon' => '🏔️',
                'color' => 'green'
            ],
            'pantai' => [
                'name' => 'Pantai',
                'icon' => '🏖️',
                'color' => 'blue'
            ],
            'sejarah' => [
                'name' => 'Bersejarah',
                'icon' => '🏛️',
                'color' => 'yellow'
            ],
            'budaya' => [
                'name' => 'Budaya',
                'icon' => '🎭',
                'color' => 'purple'
            ],
            'religi' => [
                'name' => 'Religi',
                'icon' => '🕌',
                'color' => 'indigo'
            ]
        ];
    }

    public function getCategoryInfo()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? $categories['wisata-alam'];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the main image URL
     */
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return asset('images/destinations/' . $this->main_image);
        }
        return asset('images/placeholder.jpg');
    }

    /**
     * Get gallery URLs
     */
    public function getGalleryUrlsAttribute()
    {
        if (!$this->gallery || !is_array($this->gallery)) {
            return [];
        }

        return array_map(function($image) {
            return asset('images/destinations/' . $image);
        }, $this->gallery);
    }

    /**
     * Scope for active destinations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for filtering by category
     */
    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope for search functionality
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Check if destination has entry fees
     */
    public function hasEntryFees()
    {
        return $this->entry_fees && is_array($this->entry_fees) && count($this->entry_fees) > 0;
    }

    /**
     * Get formatted entry fees
     */
    public function getFormattedEntryFees()
    {
        if (!$this->hasEntryFees()) {
            return 'Gratis';
        }

        $fees = [];
        foreach ($this->entry_fees as $type => $fee) {
            $fees[] = ucfirst($type) . ': Rp ' . number_format($fee, 0, ',', '.');
        }

        return implode(', ', $fees);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($destination) {
            $destination->slug = Str::slug($destination->name);
        });

        static::updating(function ($destination) {
            if ($destination->isDirty('name')) {
                $destination->slug = Str::slug($destination->name);
            }
        });

        // Log soft delete events
        static::deleted(function ($destination) {
            \Log::info('Destination soft deleted', [
                'id' => $destination->id,
                'name' => $destination->name,
                'deleted_at' => now()
            ]);
        });

        static::restored(function ($destination) {
            \Log::info('Destination restored', [
                'id' => $destination->id,
                'name' => $destination->name,
                'restored_at' => now()
            ]);
        });
    }
}