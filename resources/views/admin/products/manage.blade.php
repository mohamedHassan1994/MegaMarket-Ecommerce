@extends('admin.app')

@section('title', 'Manage Products')
@section('admin_page_title', 'Manage Products')

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

                <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                        <form method="GET" action="{{ url()->current() }}">
                            @csrf
                            <label for="perPage" class="me-2">Show</label>
                            <select name="perPage" id="perPage" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100, 500, 1000] as $size)
                                    <option value="{{ $size }}" {{ request('perPage', 25) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="ms-1">products per page</span>
                        </form>
                    </div>
<div class="d-flex justify-content-center mt-3">
                        {{ $products->links('vendor.pagination.bootstrap-4') }}
                    </div>
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th hidden>DB ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Enabled</th>
                                <th>Subcategories</th>
                                <th>Attributes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $products->firstItem() + $loop->index }}</td>
                                    <td hidden>{{ $product->id }}</td>

                                    {{-- Image --}}
                                    <td class="text-center">
                                        @if ($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                                alt="{{ $product->name }}" width="60" height="60" class="rounded d-block mx-auto">
                                        @elseif ($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                                alt="{{ $product->name }}" width="60" height="60" class="rounded d-block mx-auto">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>

                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        @if($product->is_enabled)
                                            <span class="badge rounded-pill bg-success px-3 py-2 fs-6">Enabled</span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary px-3 py-2 fs-6">Disabled</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $product->getCategoryTree() }}
                                    </td>

                                    {{-- Attributes --}}
                                    <td>
                                        @if($product->attributeValues->count())
                                            @foreach($product->attributeValues as $attrValue)
                                                <span class="badge bg-primary">
                                                    {{ $attrValue->attribute->name }}: 
                                                    {{ $attrValue->attributeValue ? $attrValue->attributeValue->value : $attrValue->custom_value }}
                                                </span><br>
                                            @endforeach
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info btn-md">Edit</a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-md"
                                                onclick="return confirm('Are you sure you want to delete this product?');">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $products->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection