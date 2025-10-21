@extends('admin.app')

@section('title', 'Manage Orders')
@section('admin_page_title', 'Manage Orders')

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
                            <span class="ms-1">orders per page</span>
                        </form>
                    </div>

                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Order State</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Shipping Status</th>
                                <th>Total</th>
                                <th>Action</th>
                                {{-- <th>History</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    {{-- Make order ID clickable --}}
                                    <td>
                                        <a href="{{ route('orders.edit', $order->id) }}">
                                            {{ $loop->iteration }}
                                        </a>
                                    </td>



                                    {{-- Show date with seconds --}}
                                    <td>{{ $order->created_at->format('d-m-Y H:i:s') }}</td>

                                    {{-- Customer name + email under it --}}
                                    <td>
                                        {{ $order->user->name ?? $order->customer_name ?? 'Guest' }} <br>
                                        <small class="text-muted">
                                            {{ $order->user->email ?? $order->customer_email ?? '--' }}
                                        </small>
                                    </td>

                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">Processing</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->payment_method ?? 'N/A' }}</td>
                                    <td>
                                        @if($order->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->shipping_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->shipping_status == 'shipped')
                                            <span class="badge bg-info">Shipped</span>
                                        @elseif($order->shipping_status == 'delivered')
                                            <span class="badge bg-success">Delivered</span>
                                        @elseif($order->shipping_status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->shipping_status) }}</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-primary">
                                            View / Edit
                                        </a>

                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>

                                    {{-- <td>
                                        <a href="{{ route('orders.history', $order->id) }}" class="btn btn-sm btn-info">
                                        View History
                                        </a>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
