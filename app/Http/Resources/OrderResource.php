<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        $customer = [
            'name'  => $this->customer_name ?? ($this->user->name ?? 'Guest'),
            'email' => $this->customer_email ?? ($this->user->email ?? null),
            'phone' => $this->customer_phone ?? null,
        ];

        $latestPayment = $this->payments()->latest()->first();

        return [
            'id'              => $this->id,
            'date'            => $this->created_at->format('d-m-Y H:i'),
            'customer'        => $customer,
            'status'          => $this->status,
            'payment_method'  => $this->payment_method,
            'payment_status'  => $this->payment_status,
            'shipping_status' => $this->shipping_status,
            'total_amount'    => number_format($this->total_amount, 2),
            'shipping_address'=> $this->shipping_address,
            'payment'         => $latestPayment ? [
                'method'   => $latestPayment->payment_method_type,
                'gateway'  => $latestPayment->payment_gateway,
                'status'   => $latestPayment->status,
                'amount'   => $latestPayment->amount,
                'currency' => $latestPayment->currency,
            ] : null,
            'items'           => OrderItemResource::collection($this->whenLoaded('orderItems')),
        ];
    }


}
