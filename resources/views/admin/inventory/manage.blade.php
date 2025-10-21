@extends('admin.app')

@section('title', 'Manage Inventory')
@section('admin_page_title', 'Manage Inventory')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Alerts --}}
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Warning Message --}}
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif


                <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                        <form method="GET" action="{{ url()->current() }}">
                            <label for="perPage" class="me-2">Show</label>
                            <select name="perPage" id="perPage" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="ms-1">products per page</span>
                        </form>
                    </div>

                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" width="60" height="60" class="rounded">
                                        @elseif ($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" width="60" height="60" class="rounded">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '--' }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        @if($product->stock > 1)
                                            <span class="badge bg-success">In Stock</span>
                                        @elseif($product->stock == 1)
                                            <span class="badge bg-warning">Almost out</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('inventory.edit', $product->id) }}" class="btn btn-info btn-sm">Edit Stock</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No products found.</td>
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
