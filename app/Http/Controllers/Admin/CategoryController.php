<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    // Show all categories (Manage Categories page)
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');
        $perPage = $request->get('perPage', 25); // default 25

        // Only allow sorting on certain columns to prevent SQL injection
        $allowedSorts = ['id', 'name', 'slug', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Centralize allowed perPage values
        $allowedPerPage = [10, 25, 50, 100, 500, 1000];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 25;
        }

        $categories = Category::with('parent')
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->appends([
                'sort' => $sort,
                'direction' => $direction,
                'perPage' => $perPage,
            ]);

        $products = Product::paginate($perPage);
        $products->appends(request()->query());

        return view('admin.categories.manage', compact('products','categories', 'sort', 'direction', 'perPage', 'allowedPerPage'));
    }


    // Show form to create a new category
    public function create()
    {
        $categories = Category::all();
        // Use this if only want the top category with no parents whereNull('parent_id')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function getChildren(Category $category)
    {
        try {
            // Log the request for debugging
            // \Log::info('Fetching children for category: ' . $category->id);
            
            // Get children
            $children = $category->children()->get();
            
            // Log the result
            // \Log::info('Found ' . $children->count() . ' children');
            
            return response()->json($children);
            
        } catch (\Exception $e) {
            // \Log::error('Error in getChildren: ' . $e->getMessage());
            // \Log::error($e->getTraceAsString());
            
            return response()->json([
                'error' => 'Failed to load subcategories',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getChildrenById($id)
    {
        try {
            $category = Category::findOrFail($id);
            $children = $category->children()->get();
            return response()->json($children);
        } catch (\Exception $e) {
            Log::error('Error in getChildrenById: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load subcategories',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|alpha_dash|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['parent_id'] = $validated['parent_id'] ?? null;

        // if admin didn't supply slug, remove it so model will auto-generate
        if (empty($validated['slug'])) {
            unset($validated['slug']);
        }

        Category::create($validated);

        return redirect()->route('categories.index')->with('message', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        // Exclude itself from parent list
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|alpha_dash|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['parent_id'] = $validated['parent_id'] ?? null;

        if (empty($validated['slug'])) {
            unset($validated['slug']);
        }

        $category->update($validated);

        return redirect()->route('categories.index')->with('message', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('message', 'Category deleted successfully!');
    }
        

}
