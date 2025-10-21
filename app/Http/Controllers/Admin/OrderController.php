<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 25);
        $orders = Order::with('user')->paginate($perPage);

        return view('admin.orders.manage', compact('orders'));
    }

    public function edit($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);

        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // ✅ validate only the fields you allow to be updated
        $validated = $request->validate([
            'status'          => 'required|string|in:pending,processing,completed,cancelled',
            'payment_status'  => 'required|string|in:pending,paid,failed',
            'shipping_status' => 'required|string|in:pending,shipped,delivered,returned',
        ]);

        // ✅ update order
        $order->update($validated);

        // (Optional) add to order history if your model has the relation
        if (method_exists($order, 'histories')) {
            $order->histories()->create([
                'status'          => $order->status,
                'payment_status'  => $order->payment_status,
                'shipping_status' => $order->shipping_status,
                'note'            => 'Updated by admin',
            ]);
        }

        return redirect()
            ->route('orders.index', $order->id)
            ->with('message', 'Order updated successfully!');
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'completed' || $order->status === 'cancelled') {
            return redirect()->back()->with('warning', 'This order cannot be cancelled.');
        }

        // Cancel all states
        $order->status = 'cancelled';
        $order->payment_status = 'refunded';   // or 'refunded' if that makes more sense
        $order->shipping_status = 'cancelled';  // or 'not shipped'

        $order->save();

        return redirect()->route('orders.index', $order->id)
            ->with('message', 'Order has been cancelled successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Prevent deleting completed orders
        if ($order->status === 'completed')
            return redirect()->back()->with('warning', 'Completed orders cannot be deleted.');

        $order->delete();

        return redirect()->route('orders.index')
            ->with('message', 'Order deleted successfully.');
    }

    // public function history($userId)
    // {
    //     $orders = Order::with('orderItems.product')
    //         ->where('user_id', $userId)
    //         ->latest()
    //         ->paginate(25);

    //     return view('admin.orders.history', compact('orders'));
    // }
}
