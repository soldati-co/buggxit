<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'shipping_address_id' => 'nullable|exists:addresses,id',
            'address_line1' => 'required_without:shipping_address_id|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required_without:shipping_address_id|string|max:255',
            'state' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn () => $this->input('courier_method') === 'pep')],
            'postal_code' => 'required_without:shipping_address_id|string|max:20',
            'country' => 'required_without:shipping_address_id|string|max:255',
            'phone' => 'required_without:shipping_address_id|string|max:50',
            'payment_method' => 'required|in:payfast',
            'courier_method' => 'required|in:courier_guy,pep',
            'pep_point_code' => 'required_if:courier_method,pep|nullable|string|max:20',
            'same_as_shipping' => 'nullable|boolean',
            'email' => 'required|email|max:255',
            'notes' => 'nullable|string|max:2000',
        ];
    }
}
