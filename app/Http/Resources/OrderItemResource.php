<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'product_id'    => $this->product_id,
            'name'          => $this->product_name,
            'quantity'      => (int) $this->quantity,
            'unit_price'    => number_format($this->unit_price, 2),
            'units_total'   => number_format($this->units_total, 2),
            'product'       => $this->whenLoaded('product', function () {
                return [
                    'id'    => $this->product->id ?? null,
                    'slug'  => $this->product->slug ?? null,
                ];
            }),
        ];
    }
}
