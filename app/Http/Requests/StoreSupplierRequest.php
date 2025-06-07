<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'store_id' => auth()->user()->hasRole('agent') ? 'nullable' : 'required|exists:stores,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The supplier name is required.',
            'store_id.required' => 'Please select a store for the supplier.',
            'store_id.exists' => 'The selected store is invalid.',
        ];
    }
}