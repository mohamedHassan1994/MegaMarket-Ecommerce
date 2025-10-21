<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    // list user's orders
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $orders = $request->user()->orders()
            ->with(['orderItems.product', 'payments']) // 👈 add payments
            ->paginate($perPage);

        return OrderResource::collection($orders);
    }

    // show one order (owner or admin)
    public function show(Request $request, Order $order)
    {
        // apply authorization (example) - create a policy later
        if ($order->user_id !== $request->user()->id && !$request->user()->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $order->load('orderItems.product', 'user', 'payments');

        return new OrderResource($order);
    }

    // main: store
    public function store(StoreOrderRequest $request)
    {
        $user = $request->user();
        $userId = $user ? $user->id : null;

        if (!$user && $request->filled('customer_email')) {
            $existing = User::where('email', $request->input('customer_email'))->first();
            if ($existing) $userId = $existing->id;
        }

        $customerName  = $user ? ($user->name ?? null) : $request->input('customer_name');
        $customerEmail = $user ? ($user->email ?? null) : $request->input('customer_email');
        $customerPhone = $request->input('customer_phone');

        $items = $request->input('items', []);
        DB::beginTransaction();

        DB::beginTransaction();

        try {
            $total = 0;
            $orderItemsPayload = [];
            $lowStockProducts = [];

            // 1. Validate all items first
            foreach ($items as $i) {
                $product = Product::lockForUpdate()->find($i['product_id']);

                if (!$product) {
                    throw new \Exception("Product ID {$i['product_id']} not found");
                }

                $qty = (int) $i['quantity'];
                if ($product->stock < $qty) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $unitPrice  = (float) $product->price;
                $unitsTotal = round($unitPrice * $qty, 2);

                $total += $unitsTotal;

                $orderItemsPayload[] = [
                    'product'    => $product,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $unitPrice,
                    'units_total'=> $unitsTotal,
                    'product_name' => $product->name,
                ];
            }

            // 2. Create order
            $order = Order::create([
                'user_id'         => $userId,
                'customer_name'   => $customerName,
                'customer_email'  => $customerEmail,
                'customer_phone'  => $customerPhone,
                'shipping_address'=> $request->input('shipping_address'),
                'total_amount'    => $total,
                'payment_method'  => $request->input('payment_method'), // cod | online
                'payment_status'  => 'pending',
                'shipping_status' => 'pending',
                'status'          => 'pending',
            ]);

            // 2b. Create payment record
            $payment = Payment::create([
                'order_id'             => $order->id,
                'payment_gateway'       => $order->payment_method === 'cod' ? 'cod' : 'gateway_placeholder',
                'payment_method_type'   => $order->payment_method === 'cod' ? 'cash' : null,
                'amount'                => $order->total_amount,
                'currency'              => 'EGP', // default for now
                'status'                => 'pending', // will be updated later
                'gateway_transaction_id'=> null,
            ]);


            // 3. Deduct stock + create items
            foreach ($orderItemsPayload as $payload) {
                $product = $payload['product'];

                $product->decrement('stock', $payload['quantity']);
                $product->refresh();

                if ($product->stock <= 1) {
                    $lowStockProducts[] = $product->name;
                }

                unset($payload['product']); // remove object before saving
                $payload['order_id'] = $order->id;
                OrderItem::create($payload);
            }

            DB::commit();

        $order->load('orderItems.product', 'user', 'payments');


            $warningMessage = null;
            if (!empty($lowStockProducts)) {
                $names = implode(', ', $lowStockProducts);
                $warningMessage = "⚠️ Low stock alert: {$names} has only 1 item left.";
            }

            return response()->json([
                'status'  => 'success',
                'order'   => new OrderResource($order),
                'warning' => $warningMessage,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

}
