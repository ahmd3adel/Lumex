<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Add custom authorization logic if needed
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
            'invoice_no' => 'required|string|max:50|unique:invoices,invoice_no',
            'invoice_date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'numeric',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'invoice_no.required' => 'The invoice number is required.',
            'invoice_no.unique' => 'This invoice number already exists.',
            'invoice_date.required' => 'The invoice date is required.',
            'client_id.required' => 'You must select a client.',
            'client_id.exists' => 'The selected client does not exist.',
            'product_id.required' => 'At least one product is required.',
            'product_id.*.exists' => 'One or more selected products are invalid.',
            'quantity.*.required' => 'Please specify the quantity for each product.',
            'quantity.*.min' => 'Quantity must be at least 1.',
            'price.*.required' => 'Please specify the price for each product.',
            'price.*.min' => 'Price cannot be less than 0.',
        ];
    }
}

