<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Image;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Show all products (Manage Products page)
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 25); // default 10
        // $products = Product::paginate($perPage);
        $products = Product::with(['attributeValues.attribute', 'attributeValues.attributeValue', 'primaryImage', 'images'])->paginate($perPage);
        $products->appends(request()->query());
        $categories = Category::all();
        $parent_category = Category::whereNull('parent_id')->get();

        return view('admin.products.manage', compact(['products', 'categories', 'parent_category']));
    }

    // Show form to create a new product
    public function create()
    {
        $parent_category = Category::whereNull('parent_id')->get();

        // ✅ Order attributes by sort_order
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        return view('admin.products.create', compact('parent_category', 'attributes'));
    }

    // Store new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'final_category_id' => 'required|exists:categories,id',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'is_enabled'    => 'nullable|boolean',
            'primary_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['category_id'] = $validated['final_category_id'];
        unset($validated['final_category_id']);

        $validated['user_id'] = auth()->id();
        $validated['is_enabled'] = $request->has('is_enabled');

        $product = Product::create($validated);

        // Handle Primary image
        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('products', 'public');
            $product->images()->create([
                'image_path'   => $path,
                'is_primary'   => true,
            ]);
        }

        // Handle additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        // ✅ Handle attributes
        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $attributeId => $value) {
                // Get the attribute to check its input type
                $attribute = Attribute::find($attributeId);
                
                if (!$attribute) {
                    continue;
                }
                
                if (is_array($value)) {
                    // Checkbox (multiple values)
                    foreach ($value as $val) {
                        if (!empty($val) || $val === '0') {
                            $product->attributeValues()->create([
                                'attribute_id' => $attributeId,
                                'attribute_value_id' => is_numeric($val) ? $val : null,
                                'custom_value' => !is_numeric($val) ? $val : null,
                            ]);
                        }
                    }
                } else {
                    // Single value (select, radio, text)
                    // Skip empty values except '0'
                    if (empty($value) && $value !== '0') {
                        continue;
                    }
                    
                    // For text input type, always save as custom_value
                    if ($attribute->input_type === 'text') {
                        $product->attributeValues()->create([
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => null,
                            'custom_value' => $value,
                        ]);
                    } else {
                        // For select/radio, the value is the ID of attribute_value
                        $product->attributeValues()->create([
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $value,
                            'custom_value' => null,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('products.index')->with('message', 'Product created successfully!');
    }

    // Show edit form
    public function edit(Product $product)
    {
        $parent_category = Category::whereNull('parent_id')->get();

        // ✅ Order attributes by sort_order
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        // ✅ Get product's saved attribute values (assuming you have a pivot relation)
        $productAttributes = $product->attributeValues()
            ->pluck('attribute_value_id', 'attribute_id')
            ->toArray();

        // ✅ Get the full category path for the product
        $categoryPath = [];
        $category = $product->category;
        while ($category) {
            array_unshift($categoryPath, $category);
            $category = $category->parent;
        }

        return view('admin.products.edit', compact(
            'product',
            'parent_category',
            'attributes',
            'categoryPath',
            'productAttributes'
        ));
    }

    // Update existing product
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'final_category_id' => 'required|exists:categories,id',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'is_enabled'    => 'nullable|boolean',
            'primary_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['category_id'] = $validated['final_category_id'];
        unset($validated['final_category_id']);

        $validated['is_enabled'] = $request->has('is_enabled');

        $product->update($validated);

        // Replace primary image if new one uploaded
        if ($request->hasFile('primary_image')) {
            if ($product->primaryImage) {
                Storage::disk('public')->delete($product->primaryImage->image_path);
                $product->primaryImage->delete();
            }

            $path = $request->file('primary_image')->store('products', 'public');
            $product->images()->create([
                'image_path'   => $path,
                'is_primary'   => true,
            ]);
        }

        // Add new additional images if uploaded
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        // ✅ DELETE OLD ATTRIBUTES FIRST!
        $product->attributeValues()->delete();

        // ✅ Then handle new attributes
        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $attributeId => $value) {
                // Get the attribute to check its input type
                $attribute = Attribute::find($attributeId);
                
                if (!$attribute) {
                    continue;
                }
                
                if (is_array($value)) {
                    // Checkbox (multiple values)
                    foreach ($value as $val) {
                        if (!empty($val) || $val === '0') {
                            $product->attributeValues()->create([
                                'attribute_id' => $attributeId,
                                'attribute_value_id' => is_numeric($val) ? $val : null,
                                'custom_value' => !is_numeric($val) ? $val : null,
                            ]);
                        }
                    }
                } else {
                    // Single value (select, radio, text)
                    // Skip empty values except '0'
                    if (empty($value) && $value !== '0') {
                        continue;
                    }
                    
                    // For text input type, always save as custom_value
                    if ($attribute->input_type === 'text') {
                        $product->attributeValues()->create([
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => null,
                            'custom_value' => $value,
                        ]);
                    } else {
                        // For select/radio, the value is the ID of attribute_value
                        $product->attributeValues()->create([
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $value,
                            'custom_value' => null,
                        ]);
                    }
                }
            }
        }
        
        return redirect()->route('products.index')->with('message', 'Product updated successfully!');
    }

    // Delete a product
    public function destroy(Product $product)
    {
        // Delete images from storage and DB
        foreach ($product->images as $image) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        // Finally delete the product
        $product->delete();

        return redirect()->route('products.index')->with('message', 'Product deleted successfully with its images!');
    }
}
