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

                </div>

            </div>
        </div>
    </div>
</div>
@endsection