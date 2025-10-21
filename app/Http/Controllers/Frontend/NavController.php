<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;

class NavController extends Controller
{
    public function index()
    {
        // Fetch only top-level categories (no parent)
        $categories = Category::whereNull('parent_id')
            ->with('children') // eager load subcategoriesj
            ->get();

        return view('frontend.partials.navbar', compact('categories'));
    }
}
