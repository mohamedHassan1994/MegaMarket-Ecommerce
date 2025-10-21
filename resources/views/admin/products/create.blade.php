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

                    {{-- Category Selection --}}
                    <div class="mb-3">
                        <label for="category_id" class="fw-bold mb-2">Category</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" 
                                name="category_id" 
                                id="category_id">
                            <option value="">-- Select Category --</option>
                            @foreach ($parent_category as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Dynamic Subcategory Levels --}}
                    <div id="subcategory-container">
                        {{-- Subcategory dropdowns will be dynamically inserted here --}}
                    </div>

                    {{-- Hidden field to store the final selected category --}}
                    <input type="hidden" name="final_category_id" id="final_category_id" value="{{ old('subcategory_id') }}">

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
                               value="{{ old('stock', null) }}">
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
                    @if(isset($attributes) && $attributes->count())
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
                                                value="{{ old('attributes.'.$attribute->id, '') }}">
                                        
                                        @elseif($attribute->input_type === 'select')
                                            <select name="attributes[{{ $attribute->id }}]" 
                                                    class="form-control">
                                                <option value="">-- Select {{ $attribute->name }} --</option>
                                                @foreach($attribute->values as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ (string)old('attributes.'.$attribute->id) === (string)$value->id ? 'selected' : '' }}>
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
                                                            {{ (string)old('attributes.'.$attribute->id) === (string)$value->id ? 'checked' : '' }}>
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
                                                            {{ is_array(old('attributes.'.$attribute->id)) && in_array($value->id, old('attributes.'.$attribute->id)) ? 'checked' : '' }}>
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
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const subcategoryContainer = document.getElementById('subcategory-container');
    const finalCategoryInput = document.getElementById('final_category_id');
    
    // Main category change handler
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        // Clear all subcategory dropdowns
        subcategoryContainer.innerHTML = '';
        
        if (categoryId) {
            finalCategoryInput.value = categoryId;
            loadSubcategories(categoryId, 0);
        } else {
            finalCategoryInput.value = '';
        }
    });
    
    // Function to load subcategories for a given parent
    function loadSubcategories(parentId, level) {
        fetch(`/admin/categories/${parentId}/children`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    createSubcategoryDropdown(data, parentId, level);
                }
            })
            .catch(error => {
                console.error('Error loading subcategories:', error);
            });
    }
    
    // Function to create a subcategory dropdown
    function createSubcategoryDropdown(subcategories, parentId, level) {
        // First, remove any existing dropdown at this exact level and beyond
        removeDropdownsFromLevel(level);
        
        const wrapper = document.createElement('div');
        wrapper.className = 'mb-3 subcategory-level';
        wrapper.dataset.level = level;
        
        const label = document.createElement('label');
        label.className = 'fw-bold mb-2';
        label.textContent = `Subcategory ${level > 0 ? 'Level ' + (level + 1) : ''}`;
        
        const select = document.createElement('select');
        select.className = 'form-control';
        select.dataset.level = level;
        select.dataset.parentId = parentId;
        
        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '-- Select Subcategory --';
        select.appendChild(defaultOption);
        
        // Add subcategory options
        subcategories.forEach(subcategory => {
            const option = document.createElement('option');
            option.value = subcategory.id;
            option.textContent = subcategory.name;
            select.appendChild(option);
        });
        
        // Add change event listener
        select.addEventListener('change', function() {
            const selectedId = this.value;
            
            if (selectedId) {
                finalCategoryInput.value = selectedId;
                // Remove any dropdowns after this level before loading new ones
                removeDropdownsFromLevel(level + 1);
                // Try to load next level
                loadSubcategories(selectedId, level + 1);
            } else {
                // If cleared, revert to parent category
                finalCategoryInput.value = parentId;
                removeDropdownsFromLevel(level + 1);
            }
        });
        
        wrapper.appendChild(label);
        wrapper.appendChild(select);
        subcategoryContainer.appendChild(wrapper);
    }
    
    // Function to remove dropdowns from a certain level onwards (including that level)
    function removeDropdownsFromLevel(level) {
        const dropdowns = subcategoryContainer.querySelectorAll('.subcategory-level');
        dropdowns.forEach(dropdown => {
            if (parseInt(dropdown.dataset.level) >= level) {
                dropdown.remove();
            }
        });
    }
    
    // Restore old selection on validation error
    @if(old('category_id'))
        categorySelect.dispatchEvent(new Event('change'));
    @endif
});
</script>
@endpush
@endsection