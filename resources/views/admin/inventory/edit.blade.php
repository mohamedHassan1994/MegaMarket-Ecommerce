@extends('admin.app')

@section('title', 'Edit Stock')
@section('admin_page_title', 'Edit Stock')

@section('content')
<div class="row">
    <div class="col-12 col-md-6 mx-auto">
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

                <h5 class="mb-3">Update Stock for {{ $product->name }}</h5>

                <form action="{{ route('inventory.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3 text-center">
                        @if ($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" width="250" class="rounded mb-2">
                        @elseif ($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" width="250" class="rounded mb-2">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">Note (optional)</label>
                        <input type="text" name="note" id="note" class="form-control" placeholder="e.g. Supplier restock, Damaged items">
                    </div>

                    <hr>
                        <h5 class="mt-4">Stock History</h5>
                        @if ($product->stockMovements->count())
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Old Stock</th>
                                            <th>New Stock</th>
                                            <th>Change</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->stockMovements()->latest()->take(10)->get() as $movement)
                                            <tr>
                                                <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                                <td>{{ $movement->old_stock }}</td>
                                                <td>{{ $movement->new_stock }}</td>
                                                <td>
                                                    @if ($movement->change > 0)
                                                        <span class="text-success">+{{ $movement->change }}</span>
                                                    @else
                                                        <span class="text-danger">{{ $movement->change }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $movement->note ?? '--' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No stock history available.</p>
                        @endif

                        <a href="{{ route('inventory.edit', $product->id) }}" class="btn btn-secondary btn-sm">History</a>


                    <button type="submit" class="btn btn-primary">Update Stock</button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
