@extends('admin.app')

@section('title', 'Create Product')

@section('admin_page_title', 'Create Product')


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            {{-- <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Add New Product</h5>
            </div> --}}
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
                <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    
                    @csrf

                    {{-- Product Name --}}
                    <div class="mb-3">
                        <label for="name" class="fw-bold">Product Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               placeholder="Computer" 
                               value="{{ old('name') }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Category --}}
                    <select class="form-control" name="category_id" id="category_id">
                        <option value="">-- Select Category --</option>
                        @foreach ($parent_category as $category)
                            <option value="{{ $category->id }}"
                                    data-slug="{{ $category->slug }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Subcategory --}}
                    <div class="mb-3" id="subcategory-wrapper" style="display: none;">
                        <label for="subcategory_id" class="fw-bold mb-2">Subcategory</label>
                        <select class="form-control" name="subcategory_id" id="subcategory_id">
                            <option value="">-- Select Subcategory --</option>

                            {{-- if old subcategory exists (validation fail) show it --}}
                            @if(old('subcategory_id'))
                                @php $oldSub = \App\Models\Category::find(old('subcategory_id')); @endphp
                                @if($oldSub)
                                    <option value="{{ $oldSub->id }}" selected>{{ $oldSub->name }}</option>
                                @endif
                            @endif
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="fw-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" 
                                  rows="4" 
                                  placeholder="Enter product details...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Price --}}
                    <div class="mb-3">
                        <label for="price" class="fw-bold">Price ($)</label>
                        <input type="number" step="0.01" 
                               class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" 
                               placeholder="100.00" 
                               value="{{ old('price') }}">
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Stock --}}
                    <div class="mb-3">
                        <label for="stock" class="fw-bold">Stock Quantity</label>
                        <input type="number" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" name="stock" 
                               placeholder="10" 
                               value="{{ old('stock', 0) }}">
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Images --}}
                    {{-- Main Image Selection (optional) --}}
                    <div class="mb-3">
                        <label for="primary_image" class="fw-bold">Primary Image</label>
                        <input type="file" 
                            class="form-control @error('primary_image') is-invalid @enderror" 
                            id="primary_image" 
                            name="primary_image"
                            accept="image/*">
                        @error('primary_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        {{-- Preview --}}
                        <div id="primary_image_preview" class="mt-2"></div>
                    </div>

                    {{-- Product Images --}}
                    <div class="mb-3">
                        <label for="images" class="fw-bold">Product Images</label>
                        <input type="file" 
                            class="form-control @error('images') is-invalid @enderror" 
                            id="images" 
                            name="images[]" 
                            multiple
                            accept="image/*">
                        @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        {{-- Preview --}}
                        <div id="images_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="status" class="fw-bold">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    {{-- Attributes --}}
                    @if($attributes->count())
                        <div class="mb-3">
                            <label class="fw-bold">Attributes</label>
                            <div class="row">
                                @foreach($attributes as $attribute)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ $attribute->name }}</label>

                                        @if($attribute->input_type === 'text')
                                            <input type="text" 
                                                name="attributes[{{ $attribute->id }}]" 
                                                class="form-control"
                                                value="{{ old('attributes.'.$attribute->id) }}">
                                        
                                        @elseif($attribute->input_type === 'select')
                                            <select name="attributes[{{ $attribute->id }}]" 
                                                    class="form-control">
                                                <option value="">-- Select {{ $attribute->name }} --</option>
                                                @foreach($attribute->values as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('attributes.'.$attribute->id) == $value->id ? 'selected' : '' }}>
                                                        {{ $value->value }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        @elseif($attribute->input_type === 'radio')
                                            <div>
                                                @foreach($attribute->values as $value)
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" 
                                                            name="attributes[{{ $attribute->id }}]" 
                                                            id="attr_{{ $attribute->id }}_{{ $value->id }}" 
                                                            value="{{ $value->id }}"
                                                            class="form-check-input"
                                                            {{ old('attributes.'.$attribute->id) == $value->id ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="attr_{{ $attribute->id }}_{{ $value->id }}">
                                                            {{ $value->value }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        @elseif($attribute->input_type === 'checkbox')
                                            <div>
                                                @foreach($attribute->values as $value)
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                            name="attributes[{{ $attribute->id }}][]" 
                                                            id="attr_{{ $attribute->id }}_{{ $value->id }}" 
                                                            value="{{ $value->id }}"
                                                            class="form-check-input"
                                                            @if(is_array(old('attributes.'.$attribute->id)) && in_array($value->id, old('attributes.'.$attribute->id))) checked @endif>
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


                    {{-- Enabled --}}
                    <div class="form-check mb-3">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_enabled" name="is_enabled" value="1" 
                               {{ old('is_enabled', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_enabled">Enabled</label>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-success w-100">Add Product</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection