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

                    {{-- Category Selection --}}
                    <div class="mb-3">
                        <label for="category_id" class="fw-bold mb-2">Category</label>
                        <select class="form-control @error('final_category_id') is-invalid @enderror" 
                                name="category_id" 
                                id="category_id">
                            <option value="">-- Select Category --</option>
                            @foreach ($parent_category as $category)
                                <option value="{{ $category->id }}"
                                        {{ (isset($categoryPath[0]) && $categoryPath[0]->id == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('final_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Dynamic Subcategory Levels --}}
                    <div id="subcategory-container">
                        {{-- Subcategory dropdowns will be dynamically inserted here --}}
                    </div>

                    {{-- Hidden field to store the final selected category --}}
                    <input type="hidden" name="final_category_id" id="final_category_id" value="{{ $product->category_id }}">

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="fw-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" 
                                rows="4" 
                                placeholder="Enter product details...">{{ old('description', $product->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Attributes --}}
@if($attributes->count())
    <div class="mb-3">
        <label class="fw-bold">Attributes</label>
        <div class="row">
            @foreach($attributes as $attribute)
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ $attribute->name }}</label>

                    @php
                        // Get saved values for this attribute
                        $savedAttributeValues = $product->attributeValues
                            ->where('attribute_id', $attribute->id);
                        
                        // For text input, get custom_value
                        $savedTextValue = $savedAttributeValues->first()->custom_value ?? '';
                        
                        // For select/radio, get single attribute_value_id
                        $savedSelectValue = $savedAttributeValues->first()->attribute_value_id ?? '';
                        
                        // For checkbox, get array of attribute_value_ids
                        $savedCheckboxValues = $savedAttributeValues->pluck('attribute_value_id')->toArray();
                    @endphp

                    @if($attribute->input_type === 'text')
                        <input type="text" 
                            name="attributes[{{ $attribute->id }}]" 
                            class="form-control"
                            value="{{ old('attributes.'.$attribute->id, $savedTextValue) }}">

                    @elseif($attribute->input_type === 'select')
                        <select name="attributes[{{ $attribute->id }}]" class="form-control">
                            <option value="">-- Select {{ $attribute->name }} --</option>
                            @foreach($attribute->values as $value)
                                <option value="{{ $value->id }}"
                                    {{ old('attributes.'.$attribute->id, $savedSelectValue) == $value->id ? 'selected' : '' }}>
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
                                        {{ old('attributes.'.$attribute->id, $savedSelectValue) == $value->id ? 'checked' : '' }}>
                                    <label class="form-check-label" for="attr_{{ $attribute->id }}_{{ $value->id }}">
                                        {{ $value->value }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                    @elseif($attribute->input_type === 'checkbox')
                        <div>
                            @foreach($attribute->values as $value)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" 
                                        name="attributes[{{ $attribute->id }}][]" 
                                        id="attr_{{ $attribute->id }}_{{ $value->id }}" 
                                        value="{{ $value->id }}"
                                        class="form-check-input"
                                        {{ in_array($value->id, old('attributes.'.$attribute->id, $savedCheckboxValues)) ? 'checked' : '' }}>
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

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="status" class="fw-bold">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    {{-- Enabled --}}
                    <div class="form-check mb-3">
                        <input type="checkbox" 
                            class="form-check-input" 
                            id="is_enabled" name="is_enabled" value="1" 
                            {{ old('is_enabled', $product->is_enabled) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_enabled">Enabled</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const subcategoryContainer = document.getElementById('subcategory-container');
    const finalCategoryInput = document.getElementById('final_category_id');
    
    // Category path from server
    const categoryPath = @json($categoryPath ?? []);
    
    let currentLevel = 0;
    
    // Main category change handler
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        subcategoryContainer.innerHTML = '';
        currentLevel = 0;
        
        if (categoryId) {
            finalCategoryInput.value = categoryId;
            loadSubcategories(categoryId, 0);
        } else {
            finalCategoryInput.value = '';
        }
    });
    
    // Function to load subcategories
    function loadSubcategories(parentId, level, preselectedId = null) {
        fetch(`/admin/categories/${parentId}/children`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    createSubcategoryDropdown(data, parentId, level, preselectedId);
                }
            })
            .catch(error => {
                console.error('Error loading subcategories:', error);
            });
    }
    
    // Function to create dropdown
    function createSubcategoryDropdown(subcategories, parentId, level, preselectedId = null) {
        removeDropdownsAfterLevel(level);
        
        const wrapper = document.createElement('div');
        wrapper.className = 'mb-3 subcategory-level';
        wrapper.dataset.level = level;
        
        const label = document.createElement('label');
        label.className = 'fw-bold mb-2';
        label.textContent = level === 0 ? 'Subcategory' : `Subcategory Level ${level + 1}`;
        
        const select = document.createElement('select');
        select.className = 'form-control';
        select.dataset.level = level;
        select.dataset.parentId = parentId;
        
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '-- Select Subcategory --';
        select.appendChild(defaultOption);
        
        subcategories.forEach(subcategory => {
            const option = document.createElement('option');
            option.value = subcategory.id;
            option.textContent = subcategory.name;
            if (preselectedId && subcategory.id == preselectedId) {
                option.selected = true;
            }
            select.appendChild(option);
        });
        
        select.addEventListener('change', function() {
            const selectedId = this.value;
            
            if (selectedId) {
                finalCategoryInput.value = selectedId;
                loadSubcategories(selectedId, level + 1);
            } else {
                finalCategoryInput.value = parentId;
                removeDropdownsAfterLevel(level);
            }
        });
        
        wrapper.appendChild(label);
        wrapper.appendChild(select);
        subcategoryContainer.appendChild(wrapper);
        
        currentLevel = level + 1;
        
        // If we have a preselected category and there are more levels, load next level
        if (preselectedId && categoryPath.length > level + 2) {
            const nextLevelCategory = categoryPath[level + 2];
            if (nextLevelCategory && nextLevelCategory.id) {
                loadSubcategories(preselectedId, level + 1, nextLevelCategory.id);
            }
        }
    }
    
    // Function to remove dropdowns after level
    function removeDropdownsAfterLevel(level) {
        const dropdowns = subcategoryContainer.querySelectorAll('.subcategory-level');
        dropdowns.forEach(dropdown => {
            if (parseInt(dropdown.dataset.level) > level) {
                dropdown.remove();
            }
        });
    }
    
    // Initialize with existing category path
    if (categoryPath.length > 1) {
        // Load first subcategory level
        loadSubcategories(categoryPath[0].id, 0, categoryPath[1].id);
    }
});
</script>
@endpush
@endsection
