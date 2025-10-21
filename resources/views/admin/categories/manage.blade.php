@extends('admin.app')

@section('title', 'Manage Category')
@section('admin_page_title', 'Manage Category')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm"> {{-- ✅ same shadow style as Products --}}
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
                            <label for="perPage" class="me-2">Show</label>
                            <select name="perPage" id="perPage" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                @foreach ($allowedPerPage as $size)
                                    <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="ms-1">Categories per page</span>

                            {{-- Keep sorting when changing perPage --}}
                            <input type="hidden" name="sort" value="{{ $sort }}">
                            <input type="hidden" name="direction" value="{{ $direction }}">
                        </form>
                    </div>


                    <table class="table table-bordered align-middle text-center"> {{-- ✅ bordered + align-middle --}}
                        <thead class="table-light">
                            <tr>
                                <th>#</th>

                                <th hidden>DB ID</th>

                                <th>
                                    <a href="{{ route('categories.index', ['sort' => 'name', 'direction' => $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc']) }}">
                                        Category Name
                                        @if ($sort === 'name')
                                            {!! $direction === 'asc' ? '&#9650;' : '&#9660;' !!}
                                        @endif
                                    </a>
                                </th>

                                <th>
                                    <a href="{{ route('categories.index', ['sort' => 'slug', 'direction' => $sort === 'slug' && $direction === 'asc' ? 'desc' : 'asc']) }}">
                                        Slug
                                        @if ($sort === 'slug')
                                            {!! $direction === 'asc' ? '&#9650;' : '&#9660;' !!}
                                        @endif
                                    </a>
                                </th>

                                <th>Description</th>

                                <th>
                                    <a href="{{ route('categories.index', ['sort' => 'is_active', 'direction' => $sort === 'is_active' && $direction === 'asc' ? 'desc' : 'asc']) }}">
                                        Active
                                        @if ($sort === 'is_active')
                                            {!! $direction === 'asc' ? '&#9650;' : '&#9660;' !!}
                                        @endif
                                    </a>
                                </th>

                                <th>
                                    <a href="{{ route('categories.index', ['sort' => 'created_at', 'direction' => $sort === 'created_at' && $direction === 'asc' ? 'desc' : 'asc']) }}">
                                        Parent Category
                                        @if ($sort === 'created_at')
                                            {!! $direction === 'asc' ? '&#9650;' : '&#9660;' !!}
                                        @endif
                                    </a>
                                </th>

                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($categories as $cat)
                                <tr>
                                    {{-- <td>{{ $cat->id }}</td> --}}
                                    <td>{{ $categories->firstItem() + $loop->index }}</td>
                                    <td hidden>{{ $cat->id }}</td>
                                    <td>{{ $cat->name }}</td>
                                    <td>{{ $cat->slug }}</td>
                                    <td title="{{ $cat->description }}">
                                        {{ \Illuminate\Support\Str::words($cat->description, 10, '...') }}
                                    </td>
                                    <td>{{ $cat->is_active ? 'Yes' : 'No' }}</td>
                                    <td>
                                        @if ($cat->parent)
                                            {{ implode(' > ', $cat->getParentTree()) }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('categories.edit', $cat->slug) }}" class="btn btn-info btn-md">Edit</a>

                                        <form action="{{ route('categories.destroy', $cat->slug) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-md"
                                                onclick="return confirm('Are you sure you want to delete this category?');">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ✅ Keep sort + direction when paginating --}}
                {{ $categories->appends(['sort' => $sort, 'direction' => $direction])->links('vendor.pagination.bootstrap-5') }}


            </div>
        </div>
    </div>
</div>
@endsection
