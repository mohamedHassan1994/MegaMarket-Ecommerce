@extends('admin.app')

@section('title', 'Edit Attribute')
@section('admin_page_title', 'Edit Attribute')

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

                {{-- Attribute Form --}}
                <form id="attributeForm" action="{{ route('attributes.update', $attribute->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Attribute Name --}}
                    <div class="mb-3">
                        <label for="name" class="fw-bold">Attribute Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               placeholder="Color, Size, Material" 
                               value="{{ old('name', $attribute->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Input Type --}}
                    <div class="mb-3">
                        <label for="input_type" class="fw-bold">Input Type</label>
                        <select name="input_type" id="input_type" class="form-control">
                            <option value="text" {{ old('input_type', $attribute->input_type) == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="select" {{ old('input_type', $attribute->input_type) == 'select' ? 'selected' : '' }}>Select</option>
                            <option value="radio" {{ old('input_type', $attribute->input_type) == 'radio' ? 'selected' : '' }}>Radio</option>
                            <option value="checkbox" {{ old('input_type', $attribute->input_type) == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                        </select>
                        @error('input_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Is Required --}}
                    <div class="form-check mb-3">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_required" name="is_required" value="1" 
                               {{ old('is_required', $attribute->is_required) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_required">Required</label>
                    </div>

                    {{-- Attribute Values --}}
                    <div class="mb-3" id="attribute-values-wrapper" style="display: none;">
                        <label for="values" class="fw-bold">Attribute Values (comma separated)</label>
                        <input type="text" 
                            class="form-control" 
                            id="values" 
                            name="values" 
                            placeholder="Red, Blue, Green" 
                            value="{{ old('values', $attribute->values->pluck('value')->implode(', ')) }}">
                        <small class="text-muted">Only used for select, radio, or checkbox types</small>
                    </div>


                    <button type="submit" id="submitBtn" class="btn btn-success w-100">Update Attribute</button>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- Show values input if input_type requires it --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputType = document.getElementById('input_type');
        const valuesWrapper = document.getElementById('attribute-values-wrapper');

        function toggleValues() {
            const val = inputType.value;
            if (['select', 'radio', 'checkbox'].includes(val)) {
                valuesWrapper.style.display = 'block';
            } else {
                valuesWrapper.style.display = 'none';
            }
        }

        inputType.addEventListener('change', toggleValues);
        toggleValues(); // initial check
    });
</script>

@endsection
