@extends('admin.app')

@section('title', 'Edit Product')
@section('admin_page_title', 'Edit Product')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">

            <div class="card-body">

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Success Message --}}
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Product Form --}}
                <form action="{{ route('products.update', $product->id) }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Product Name --}}
                    <div class="mb-3">
                        <label for="name" class="fw-bold">Product Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $product->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Category --}}
                    <div class="mb-3">
                        <label for="category_id" class="fw-bold mb-2">Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="">-- Select Category --</option>
                            @foreach ($parent_category as $category)
                                <option value="{{ $category->id }}" 
                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Subcategory --}}
                    <div class="mb-3" id="subcategory-wrapper" style="{{ $product->subcategory_id ? '' : 'display: none;' }}">
                        <label for="subcategory_id" class="fw-bold mb-2">Subcategory</label>
                        <select class="form-control" name="subcategory_id" id="subcategory_id">
                            <option value="">-- Select Subcategory --</option>
                            @foreach ($subcategories as $subcat)
                                <option value="{{ $subcat->id }}" {{ $product->subcategory_id == $subcat->id ? 'selected' : '' }}>
                                    {{ $subcat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 🔹 Attributes --}}
                    @if($attributes->count())
                        <div class="mb-3">
                            <label class="fw-bold">Attributes</label>
                            <div class="row">
                                @foreach($attributes as $attribute)
                                    <div class="col-md-6 mb-3">
                                        <label for="attr_{{ $attribute->id }}" class="form-label">{{ $attribute->name }}</label>

                                        @if($attribute->input_type === 'text')
                                            <input type="text" 
                                                name="attributes[{{ $attribute->id }}]" 
                                                id="attr_{{ $attribute->id }}" 
                                                class="form-control"
                                                value="{{ old('attributes.'.$attribute->id, $product->attributes->where('attribute_id', $attribute->id)->first()->custom_value ?? '') }}">
                                        
                                        @elseif($attribute->input_type === 'select')
                                            <select name="attributes[{{ $attribute->id }}]" id="attr_{{ $attribute->id }}" 
                                                    class="form-control">
                                                <option value="">-- Select {{ $attribute->name }} --</option>
                                                @foreach($attribute->values as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('attributes.'.$attribute->id, $product->attributes->where('attribute_id', $attribute->id)->first()->attribute_value_id ?? '') == $value->id ? 'selected' : '' }}>
                                                        {{ $value->value }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        @elseif($attribute->input_type === 'radio')
                                            <div>
                                                @foreach($attribute->values as $value)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                            name="attributes[{{ $attribute->id }}]" 
                                                            id="attr_{{ $attribute->id }}_{{ $value->id }}" 
                                                            value="{{ $value->id }}"
                                                            {{ old('attributes.'.$attribute->id, $product->attributes->where('attribute_id', $attribute->id)->first()->attribute_value_id ?? '') == $value->id ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="attr_{{ $attribute->id }}_{{ $value->id }}">
                                                            {{ $value->value }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Price --}}
                    <div class="mb-3">
                        <label for="price" class="fw-bold">Price ($)</label>
                        <input type="number" step="0.01" 
                               class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" 
                               value="{{ old('price', $product->price) }}">
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Stock --}}
                    <div class="mb-3">
                        <label for="stock" class="fw-bold">Stock Quantity</label>
                        <input type="number" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" name="stock" 
                               value="{{ old('stock', $product->stock) }}">
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Current Image --}}
                    <div class="mb-3 text-center">
                        <label class="fw-bold d-block">Current Image</label>

                        @if ($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                alt="{{ $product->name }}" 
                                style="max-width: 250px; height: auto;" 
                                class="rounded shadow-sm img-fluid">
                        @elseif ($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                alt="{{ $product->name }}" 
                                style="max-width: 250px; height: auto;" 
                                class="rounded shadow-sm img-fluid">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </div>

                    {{-- Replace Primary Image --}}
                    <div class="mb-3">
                        <label for="primary_image" class="fw-bold">Replace Primary Image (optional)</label>
                        <input type="file" class="form-control" id="primary_image" name="primary_image" accept="image/*">
                        <div id="primary_image_preview" class="mt-2"></div>
                    </div>

                    {{-- Replace Additional Images --}}
                    <div class="mb-3">
                        <label for="images" class="fw-bold">Replace/Add Product Images</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        <div id="images_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
