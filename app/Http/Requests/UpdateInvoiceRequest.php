<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
class UpdateInvoiceRequest extends FormRequest
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
'invoice_no' => [
    'required',
    'numeric',
    Rule::unique('invoices')
        ->where(function ($query) {
            return $query->where('created_by', Auth::id());
        })
        ->ignore($this->route('invoice')), // استبعاد الفاتورة الحالية
],
            'invoice_date'  => ['required', 'date'],
            'client_id'     => ['required', 'exists:clients,id'],
            'store_id'      => ['nullable', 'exists:stores,id'], // أو required حسب الدور
            'discount'      => ['nullable', 'numeric', 'min:0'],
            'total'         => ['required', 'numeric', 'min:0'],
            'net_total'     => ['required', 'numeric', 'min:0'],
    
            // Products
            'product_id'    => ['required', 'array'],
            'product_id.*'  => ['required', 'exists:products,id'],
            'quantity'      => ['required', 'array'],
            'quantity.*'    => ['required', 'numeric', 'min:1'],
            'price'         => ['required', 'array'],
            'price.*'       => ['required', 'numeric', 'min:0'],
            'subtotal'      => ['nullable', 'array'],
        ];
    }
    
}
