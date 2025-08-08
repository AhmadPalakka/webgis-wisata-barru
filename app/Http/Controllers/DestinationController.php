<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::active(); // Hanya destinasi aktif
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }
        
        $destinations = $query->orderBy('name', 'asc')->paginate(9);
        $categories = Destination::getCategories();
        
        return view('destinations.index', compact('destinations', 'categories'));
    }
    
    public function show(Destination $destination)
    {
        return view('destinations.show', compact('destination'));
    }
}