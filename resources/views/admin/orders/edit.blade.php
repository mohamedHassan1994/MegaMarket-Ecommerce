@extends('admin.app')

@section('title', 'Edit Order')
{{-- @section('admin_page_title', 'Edit Order') --}}

@section('content')
<div class="row">
    <div class="col-12 col-md-10 mx-auto">
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Order #{{ $order->id }} Details</h5>

                    @if($order->status !== 'cancelled' && $order->status !== 'completed')
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" 
                            onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-m">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                </div>


                {{-- Customer Info --}}
                <div class="mb-4">
                    <h6>Customer Information</h6>
                    <p>
                        <strong>Customer:</strong> {{ $order->customer_name ?? $order->user->name ?? 'Guest' }}<br>
                        <strong>Email:</strong> {{ $order->customer_email ?? $order->user->email ?? 'N/A' }}
                    </p>
                </div>

                {{-- Order Items --}}
                <div class="mb-4">
                    <h6>Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Discount</th>
                                    <th>Discounted Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    @php $product = $item->product; @endphp
                                    <tr>
                                        {{-- Item (image + name side by side) --}}
                                        <td class="text-start">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if ($product && $product->primaryImage)
                                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                                            alt="{{ $product->name }}" width="60" height="60" 
                                                            class="rounded">
                                                    @elseif ($product && $product->images->first())
                                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                                            alt="{{ $product->name }}" width="60" height="60" 
                                                            class="rounded">
                                                    @else
                                                        <span class="text-muted">No Image</span>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold">{{ $product->name ?? 'Deleted Product' }}</div>
                                                </div>
                                            </div>
                                        {{-- Unit price --}}
                                        <td>EGP {{ number_format($item->unit_price, 2) }}</td>

                                        {{-- Quantity --}}
                                        <td>{{ $item->quantity }}</td>

                                        {{-- Subtotal (before discount) --}}
                                        <td>EGP {{ number_format($item->unit_price * $item->quantity, 2) }}</td>

                                        {{-- Discount (per unit) --}}
                                        <td>
                                            EGP {{ number_format($item->discount ?? 0, 2) }}
                                        </td>

                                        {{-- Discounted unit price --}}
                                        <td>
                                            EGP {{ number_format(max($item->unit_price - ($item->discount ?? 0), 0), 2) }}
                                        </td>

                                        {{-- Total (after discount * quantity) --}}
                                        <td>
                                            EGP {{ number_format(max(($item->unit_price - ($item->discount ?? 0)), 0) * $item->quantity, 2) }}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Order Totals --}}
                <div class="mb-4">

                    {{-- Subtotal Row --}}
                    <div class="border p-2 mb-2 bg-light" style="color: black; font-size: 18px;">
                        <strong>Subtotal:</strong> 
                        EGP {{ number_format($order->items_subtotal, 2) }}
                    </div>

                    {{-- Middle Details --}}
                    <p><strong style="color: black">Shipping Method:</strong> {{ $order->shipping_method ?? 'N/A' }} - 
                    EGP {{ number_format($order->shipping_cost ?? 0, 2) }}</p>
                    <p><strong style="color: black">COD Fees:</strong> EGP {{ number_format($order->cod_fees ?? 0, 2) }}</p>
                    <p><strong style="color: black">Taxes:</strong> EGP {{ number_format($order->tax_total ?? 0, 2) }}</p>
                    <p><strong style="color: black">Promotion:</strong> {{ $order->promotion_type ?? 'N/A' }} - 
                    EGP {{ number_format($order->promotion_amount ?? 0, 2) }}</p>
                    <p><strong style="color: black">Shipping Notes:</strong> {{ $order->shipping_notes ?? 'N/A' }}</p>

                    {{-- Discounts --}}
                    @if ($order->discounts->count())
                        <div class="border p-2 mb-2 bg-light" style="color: black; font-size: 18px;">
                            <strong>Discounts:</strong>
                            <ul class="mb-0">
                                @foreach ($order->discounts as $discount)
                                    <li>
                                        Code: <strong>{{ $discount->code }}</strong> 
                                        ({{ ucfirst($discount->type) }} - {{ $discount->value }})
                                        → Applied: EGP {{ number_format($discount->pivot->applied_amount, 2) }}
                                    </li>
                                @endforeach
                            </ul>
                            <div><strong>Total Discount:</strong> EGP {{ number_format($order->discount_total, 2) }}</div>
                        </div>
                    @endif

                    {{-- Total Row --}}
                    <div class="border p-2 mt-3 bg-light" style="color: black; font-size: 25px;">
                        <strong>Total:</strong> 
                        EGP {{ number_format($order->grand_total, 2) }}
                    </div>
                </div>

                {{-- Edit Form --}}
                <form action="{{ route('orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label" style="color: black">Order Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="payment_status" class="form-label" style="color: black">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="shipping_status" class="form-label" style="color: black">Shipping Status</label>
                            <select name="shipping_status" id="shipping_status" class="form-select">
                                <option value="pending" {{ $order->shipping_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="shipped" {{ $order->shipping_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->shipping_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="returned" {{ $order->shipping_status == 'returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="color: black">Payment Method</label>
                        <input type="text" class="form-control" value="{{ $order->payment_method ?? 'N/A' }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Order</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                </form>

                <hr>

                {{-- Order History --}}
                <h5 class="mt-4">Order History</h5>
                @if ($order->histories && $order->histories->count())
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Order Status</th>
                                    <th>Payment Status</th>
                                    <th>Shipping Status</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->histories()->latest()->get() as $history)
                                    <tr>
                                        <td>{{ $history->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ ucfirst($history->status) }}</td>
                                        <td>{{ ucfirst($history->payment_status) }}</td>
                                        <td>{{ ucfirst($history->shipping_status) }}</td>
                                        <td>{{ $history->note ?? '--' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No order history available.</p>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
