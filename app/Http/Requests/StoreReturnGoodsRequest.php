<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnGoodsRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'return_no' => 'required|string|max:50|unique:invoices,invoice_no',
            'return_date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'store_id' => 'required|exists:stores,id',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'numeric',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'notes' => 'string',
            'quantity.*' => 'required|numeric|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
        ];
    }
}
