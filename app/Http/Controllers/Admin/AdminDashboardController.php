<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $parent_category = Category::whereNull('parent_id')->get();
        return view('admin.dashboard', compact('parent_category'));
    }
}
