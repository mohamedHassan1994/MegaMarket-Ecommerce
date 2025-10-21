<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Assuming you have a Product model
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('status','active')->take(8)->get();
        $categories = Category::take(6)->get();

        return view('frontend.home', compact('featuredProducts', 'categories'));
    }

    public function products()
    {
        $products = Product::paginate(12);
        return view('frontend.products.index', compact('products'));
    }

    public function showProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('frontend.products.show', compact('product'));
    }

    public function addToCart($id)
    {
        // Handle cart logic
        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // Save email to DB or service
        return redirect()->back()->with('success', 'Subscribed successfully!');
    }
}
