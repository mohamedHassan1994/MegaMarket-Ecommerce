@extends('admin.app')

@section('title', 'Edit Category')
@section('admin_page_title', 'Edit Category')

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

                <form action="{{ route('categories.update', $category->slug) }}" method="POST">
                    @csrf
                    @method('PUT')


                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="fw-bold mb-2">Category Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $category->name }}" required>
                    </div>

                    {{-- Slug (optional) --}}
                    <div class="mb-3">
                        <label for="slug" class="fw-bold mb-2">Slug (optional)</label>
                        <input type="text" class="form-control" name="slug" id="slug"
                            placeholder="e.g. electronics" value="{{ old('slug', $category->slug ?? '') }}">
                        <small class="form-text text-muted">Leave blank to auto-generate from name.</small>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="fw-bold mb-2">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3">{{ $category->description }}</textarea>
                    </div>

                    {{-- Parent Category --}}
                    <div class="mb-3">
                        <label for="parent_id" class="fw-bold mb-2">Parent Category (optional)</label>
                        <select class="form-control" name="parent_id" id="parent_id">
                            <option value="">-- None (Top Level Category) --</option>
                            @foreach ($categories as $catOption)
                                @if($catOption->id !== $category->id) {{-- prevent self as parent --}}
                                    <option value="{{ $catOption->id }}" {{ $category->parent_id == $catOption->id ? 'selected' : '' }}>
                                        {{ $catOption->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Active --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">Update Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
