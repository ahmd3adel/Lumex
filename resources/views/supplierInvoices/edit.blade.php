@extends('layouts.index')
@section('title', __('edit_supplier_invoice'))
@section('content')
<style>
  /* تنسيق عام للمودال */
  #viewInvoiceModal .modal-content {
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  }
  
  /* تنسيق الهيدر */
  #viewInvoiceModal .modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 15px 20px;
  }
  
  #viewInvoiceModal .modal-title {
    font-weight: 700;
    color: #333;
  }
  
  /* تنسيق البودي */
  #viewInvoiceModal .modal-body {
    padding: 20px;
  }
  
  #viewInvoiceModal .modal-body p {
    margin-bottom: 12px;
    font-size: 16px;
    display: flex;
  }
  
  #viewInvoiceModal .modal-body strong {
    min-width: 120px;
    color: #555;
    font-weight: 600;
  }
  
  #viewInvoiceModal .modal-body span {
    color: #333;
    word-break: break-word;
  }
  
  /* تنسيق الفوتر */
  #viewInvoiceModal .modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
    padding: 15px 20px;
  }
  
  /* تنسيق الزر */
  #viewInvoiceModal .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    padding: 8px 20px;
    font-weight: 500;
  }
  
  #viewInvoiceModal .btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
  }
  
  /* تنسيق للعناوين الطويلة */
  @media (max-width: 768px) {
    #viewInvoiceModal .modal-body p {
      flex-direction: column;
    }
    
    #viewInvoiceModal .modal-body strong {
      margin-bottom: 5px;
    }
  }
</style>
<div class="content-wrapper">

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('supplier_invoices.update', $supplierInvoice->id) }}" method="POST" id="invoice-form">
            @csrf
            @method('PUT')

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('edit_supplier_invoice') }}</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- رقم الفاتورة -->
                        <div class="form-group col-md-4">
                            <label for="invoice_no">{{ __('invoice_no') }}</label>
                            <input type="text" name="invoice_no" id="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" 
                                   value="{{ old('invoice_no', $supplierInvoice->invoice_no) }}" required>
                            @error('invoice_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- المورد -->
                        <div class="form-group col-md-4">
                            <label for="supplier_id">{{ __('supplier') }}</label>
                            <select name="supplier_id" id="supplier_id" class="form-control select2 @error('supplier_id') is-invalid @enderror" required>
                                <option value="">{{ __('choose') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $supplierInvoice->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- المخزن -->
                        <div class="form-group col-md-4">
                            <label for="store_id">{{ __('store') }}</label>
                            <select name="store_id" id="store_id" class="form-control select2 @error('store_id') is-invalid @enderror" required>
                                <option value="">{{ __('choose') }}</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id', $supplierInvoice->store_id) == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- التاريخ -->
                        <div class="form-group col-md-4">
                            <label for="invoice_date">{{ __('invoice_date') }}</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                   value="{{ old('invoice_date', $supplierInvoice->invoice_date->format('Y-m-d')) }}" required>
                            @error('invoice_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- عدد القطع -->
                        <div class="form-group col-md-4">
                            <label for="pieces_no">{{ __('pieces') }}</label>
                            <input type="number" name="pieces_no" id="pieces_no" class="form-control @error('pieces_no') is-invalid @enderror" 
                                   value="{{ old('pieces_no', $supplierInvoice->pieces_no) }}" min="0">
                            @error('pieces_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ملاحظات -->
                        <div class="form-group col-md-4">
                            <label for="notes">{{ __('notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="1">{{ old('notes', $supplierInvoice->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- المنتجات -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('products') }}</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered" id="items-table">
                        <thead>
                            <tr>
                                <th width="30%">{{ __('product') }}</th>
                                <th width="15%">{{ __('quantity') }}</th>
                                <th width="15%">{{ __('price') }}</th>
                                <th width="15%">{{ __('subtotal') }}</th>
                                {{-- <th width="10%">{{ __('unit_type') }}</th> --}}
                                <th width="15%">
                                    <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                                        <i class="fas fa-plus"></i> {{ __('add') }}
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            @php
                                $oldItems = old('items', $supplierInvoice->items->map(fn($item) => [
                                    'product' => $item->supplier_product_id,
                                    'quantity' => $item->quantity,
                                    'price' => $item->unit_price,
                                    'total_price' => $item->total_price,
                                    'unit_type' => $item->unit_type,
                                ])->toArray());
                            @endphp

                            @foreach($oldItems as $index => $item)
                            <tr class="item-row">
                                <td>
                                    <select name="items[{{ $index }}][product]" class="form-control product-select @error('items.'.$index.'.product') is-invalid @enderror" required>
                                        <option value="">{{ __('select_product') }}</option>
                                        @foreach($supplierProducts as $product)
                                            <option value="{{ $product->id }}" 
                                                data-price="{{ $product->price }}"
                                                {{ $item['product'] == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('items.'.$index.'.product')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $index }}][quantity]" 
                                           class="form-control quantity" 
                                           value="{{ $item['quantity'] }}" 
                                           min="0.01" step="0.01" required>
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $index }}][price]" 
                                           class="form-control price" 
                                           value="{{ $item['price'] }}" 
                                           min="0.01" step="0.01" required>
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $index }}][total_price]" 
                                           class="form-control subtotal" 
                                           value="{{ $item['total_price'] }}" 
                                           readonly>
                                </td>
                                {{-- <td>
                                    <select name="items[{{ $index }}][unit_type]" class="form-control">
                                        <option value="piece" {{ $item['unit_type'] == 'piece' ? 'selected' : '' }}>{{ __('piece') }}</option>
                                        <option value="box" {{ $item['unit_type'] == 'box' ? 'selected' : '' }}>{{ __('box') }}</option>
                                        <option value="kg" {{ $item['unit_type'] == 'kg' ? 'selected' : '' }}>{{ __('kg') }}</option>
                                    </select>
                                </td> --}}
                                <td>
                                    @if($index > 0)
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>{{ __('total') }}:</strong></td>
                                <td>
                                    <input type="number" name="total" id="total" 
                                           class="form-control" 
                                           value="{{ old('total', $supplierInvoice->total) }}" 
                                           readonly>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>{{ __('discount') }}:</strong></td>
                                <td>
                                    <input type="number" name="discount" id="discount" 
                                           class="form-control" 
                                           value="{{ old('discount', $supplierInvoice->discount ?? 0) }}" 
                                           min="0" step="0.01">
                                </td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>{{ __('net_total') }}:</strong></td>
                                <td>
                                    <input type="number" name="net_total" id="net_total" 
                                           class="form-control" 
                                           value="{{ old('net_total', $supplierInvoice->net_total ?? $supplierInvoice->total) }}" 
                                           readonly>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('save') }}
                </button>
                <a href="{{ route('supplier_invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> {{ __('cancel') }}
                </a>
            </div>
        </form>
    </div>
</section>
</div>
@endsection

@push('cssModal')
<link rel="stylesheet" href="{{ asset('dist/css/myCustomTable.css') }}">
<style>
    .item-row:hover {
        background-color: #f8f9fa;
    }
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
    }
</style>
@endpush

@push('jsModal')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// متغير لحساب عدد الصفوف
let rowCount = {{ count($oldItems) }};

// دالة لحساب المجاميع
function calculateTotals() {
    let total = 0;
    let totalPieces = 0;

    // حساب المجموع لكل صف
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const subtotal = qty * price;
        
        // تحديث حقل المجموع الفرعي
        row.querySelector('.subtotal').value = subtotal.toFixed(2);
        
        // إضافة إلى المجموع الكلي
        total += subtotal;
        totalPieces += qty;
    });

    // تحديث الحقول الرئيسية
    document.getElementById('total').value = total.toFixed(2);
    document.getElementById('pieces_no').value = totalPieces;

    // حساب الخصم وصافي المبلغ
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    document.getElementById('net_total').value = (total - discount).toFixed(2);
}

// دالة لإضافة صف جديد
function addRow() {
    const tbody = document.getElementById('items-body');
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';
    
    // إنشاء خلايا الصف الجديد
    newRow.innerHTML = `
        <td>
            <select name="items[${rowCount}][product]" class="form-control product-select" required>
                <option value="">{{ __('select_product') }}</option>
                @foreach($supplierProducts as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="items[${rowCount}][quantity]" class="form-control quantity" min="0.01" step="0.01" required>
        </td>
        <td>
            <input type="number" name="items[${rowCount}][price]" class="form-control price" min="0.01" step="0.01" required>
        </td>
        <td>
            <input type="number" name="items[${rowCount}][total_price]" class="form-control subtotal" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    // إضافة الصف الجديد
    tbody.appendChild(newRow);
    
    // ربط الأحداث للصف الجديد
    bindRowEvents(newRow);
    
    // زيادة العداد
    rowCount++;
}

// دالة لحذف صف
function removeRow(button) {
    const row = button.closest('tr');
    if (document.querySelectorAll('.item-row').length > 1) {
        row.remove();
        calculateTotals();
    } else {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("warning") }}',
            text: '{{ __("at_least_one_item_required") }}'
        });
    }
}

// دالة لربط أحداث الصف
function bindRowEvents(row) {
    // ربط حدث تغيير الكمية والسعر
    row.querySelector('.quantity').addEventListener('input', calculateTotals);
    row.querySelector('.price').addEventListener('input', calculateTotals);
    
    // ربط حدث تغيير المنتج لتعيين السعر التلقائي
    row.querySelector('.product-select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        if (price) {
            row.querySelector('.price').value = price;
            calculateTotals();
        }
    });
}

// دالة لربط جميع الأحداث عند التحميل
function bindEvents() {
    // ربط أحداث الخصم
    document.getElementById('discount').addEventListener('input', calculateTotals);
    
    // ربط أحداث لكل صف موجود
    document.querySelectorAll('.item-row').forEach(row => {
        bindRowEvents(row);
    });
}

// تنفيذ عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    bindEvents();
    calculateTotals(); // حساب القيم الأولية
});
</script>

@endpush