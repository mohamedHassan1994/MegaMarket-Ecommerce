<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = [
            ['id' => 1, 'name' => 'Modern T-Shirt', 'price' => 29.99, 'image' => '/images/product1.jpg'],
            ['id' => 2, 'name' => 'Sneakers', 'price' => 59.99, 'image' => '/images/product2.jpg'],
            ['id' => 3, 'name' => 'Smart Watch', 'price' => 129.99, 'image' => '/images/product3.jpg'],
        ];

        return view('frontend.products.index', compact('products'));
    }

    public function show($id)
    {
        $product = [
            'id' => $id,
            'name' => 'Modern T-Shirt',
            'price' => 29.99,
            'description' => 'A premium quality T-shirt with a modern design.',
            'image' => '/images/product1.jpg'
        ];

        return view('frontend.products.show', compact('product'));
    }
}
