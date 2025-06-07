<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
public function rules()
{
    return [
        'items' => 'required|array|min:1',
        'items.*.product' => 'required|exists:supplier_products,id',
        'items.*.quantity' => 'required|numeric|min:1',
        'items.*.price' => 'required|numeric|min:0',
    ];
}

}
