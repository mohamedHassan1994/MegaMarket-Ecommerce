<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        // Allow guests to place orders too
        return true;
    }

    public function rules()
    {
        $rules = [
            'payment_method' => ['required', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'shipping_address' => ['nullable','array'],
            'shipping_address.line1' => ['required_with:shipping_address','string'],
            'shipping_address.city' => ['required_with:shipping_address','string'],
            'shipping_address.postal_code' => ['nullable','string'],
            'shipping_address.country' => ['nullable','string'],
        ];

        // If the request is from a guest (no authenticated user), require name + email
        if (! $this->user()) {
            $rules['customer_name'] = ['required','string','max:255'];
            $rules['customer_email'] = ['required','email','max:255'];
            $rules['customer_phone'] = ['nullable','string','max:30'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'payment_method.required' => 'Please select a payment method.',

            'items.required' => 'Your order must contain at least one item.',
            'items.*.product_id.required' => 'Each item must include a product ID.',
            'items.*.product_id.exists'   => 'One or more of the selected products do not exist in our store.',
            'items.*.quantity.required'   => 'Each item must have a quantity.',
            'items.*.quantity.min'        => 'Quantity must be at least 1.',

            'shipping_address.line1.required_with' => 'Shipping address line 1 is required when providing an address.',
            'shipping_address.city.required_with'  => 'City is required when providing an address.',

            'customer_name.required'  => 'Guest checkout requires a name.',
            'customer_email.required' => 'Guest checkout requires an email address.',
            'customer_email.email'    => 'Please provide a valid email address.',
        ];
    }
}
