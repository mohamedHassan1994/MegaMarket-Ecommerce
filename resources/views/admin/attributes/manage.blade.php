@extends('admin.app')

@section('title', 'Manage Attributes')
@section('admin_page_title', 'Manage Attributes')

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
                                    <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="ms-1">attributes per page</span>
                        </form>
                    </div>

                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Input Type</th>
                                <th>Required</th>
                                <th>Values</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attributes as $attribute)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attribute->name }}</td>
                                    <td>{{ ucfirst($attribute->input_type) }}</td>
                                    <td>{{ $attribute->is_required ? 'Yes' : 'No' }}</td>
                                    <td>
                                        @if($attribute->values && $attribute->values->count())
                                            {{ $attribute->values->pluck('value')->join(', ') }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('attributes.move_up', $attribute->id) }}" class="btn btn-sm btn-secondary">↑</a>
                                        <a href="{{ route('attributes.move_down', $attribute->id) }}" class="btn btn-sm btn-secondary">↓</a>

                                        <a href="{{ route('attributes.edit', $attribute->id) }}" class="btn btn-info btn-md">Edit</a>
                                        <form action="{{ route('attributes.destroy', $attribute->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-md"
                                                onclick="return confirm('Are you sure you want to delete this attribute?');">
                                                Delete
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No attributes found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $attributes->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
