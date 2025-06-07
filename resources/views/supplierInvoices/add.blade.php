@extends('layouts.index')
@section('title', __('add_supplier_invoice'))

@section('content')
<div class="content-wrapper">

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('supplier_invoices.store') }}" method="POST">
            @csrf

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('add_supplier_invoice') }}</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- رقم الفاتورة -->
                        <div class="form-group col-md-4">
                            <label for="invoice_no">{{ __('invoice_no') }}</label>
                            <input type="number" name="invoice_no" id="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no') }}">
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
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- المخزن -->
                        <div class="form-group col-md-4">
                            <label for="store_id">{{ __('store') }}</label>
                            <select name="store_id" id="store_id" class="form-control select2 @error('store_id') is-invalid @enderror">
                                <option value="">{{ __('choose') }}</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- التاريخ -->
                        <div class="form-group col-md-4">
                            <label for="invoice_date">{{ __('invoice_date') }}</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                            @error('invoice_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- عدد القطع -->
                        <div class="form-group col-md-4">
                            <label for="pieces_no">{{ __('pieces') }}</label>
                            <input type="number" name="pieces_no" id="pieces_no" class="form-control @error('pieces_no') is-invalid @enderror" value="{{ old('pieces_no', 0) }}">
                            @error('pieces_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ملاحظات -->
                        <div class="form-group col-md-4">
                            <label for="notes">{{ __('notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="1">{{ old('notes') }}</textarea>
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
                                <th>{{ __('product') }}</th>
                                <th>{{ __('quantity') }}</th>
                                <th>{{ __('price') }}</th>
                                <th>{{ __('subtotal') }}</th>
                                <th>{{ __('notes') }}</th>
                                <th>
                                    <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            @php
                                $oldItems = old('items', [['product' => '', 'quantity' => '', 'price' => '', 'notes' => '']]);
                            @endphp
                            
                            @foreach($oldItems as $index => $item)
                            <tr>
                                <td>
                                    <select name="items[{{ $index }}][product]" class="form-control @error('items.'.$index.'.product') is-invalid @enderror">
                                        <option value="">{{ trans('Select Product') }}</option>
                                        @foreach($supplierProducts as $product)
                                            <option value="{{ $product->id }}" {{ $item['product'] == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('items.'.$index.'.product')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity @error('items.'.$index.'.quantity') is-invalid @enderror" required value="{{ $item['quantity'] }}">
                                    @error('items.'.$index.'.quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $index }}][price]" class="form-control price @error('items.'.$index.'.price') is-invalid @enderror" required value="{{ $item['price'] }}">
                                    @error('items.'.$index.'.price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td><input type="text" class="form-control subtotal" readonly></td>
                                <td>
                                    <input type="text" name="items[{{ $index }}][notes]" class="form-control @error('items.'.$index.'.notes') is-invalid @enderror" value="{{ $item['notes'] }}">
                                    @error('items.'.$index.'.notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @if($index > 0)
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">×</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- الإجماليات -->
            <div class="card card-info">
                <div class="card-body row">
                    <div class="form-group col-md-4">
                        <label for="total">{{ __('total') }}</label>
                        <input type="number" name="total" id="total" class="form-control" readonly value="{{ old('total', 0) }}">
                        @error('total')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="discount">{{ __('discount') }}</label>
                        <input type="number" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', 0) }}">
                        @error('discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="net_total">{{ __('net_total') }}</label>
                        <input type="number" name="net_total" id="net_total" class="form-control" readonly value="{{ old('net_total', 0) }}">
                        @error('net_total')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- حفظ -->
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('save') }}
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('cssModal')
<link rel="stylesheet" href="{{ asset('dist/css/myCustomTable.css') }}">
@endpush

@push('jsModal')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let rowIndex = {{ count($oldItems) }};

    function addRow() {
        const row = `
            <tr>
                <td>
                    <select name="items[${rowIndex}][product]" class="form-control" required>
                        <option value="">{{ trans('Select Product') }}</option>
                        @foreach($supplierProducts as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="items[${rowIndex}][quantity]" class="form-control quantity" required></td>
                <td><input type="number" name="items[${rowIndex}][price]" class="form-control price" required></td>
                <td><input type="text" class="form-control subtotal" readonly></td>
                <td><input type="text" name="items[${rowIndex}][notes]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">×</button></td>
            </tr>
        `;
        $('#items-body').append(row);
        rowIndex++;
    }

    function removeRow(button) {
        $(button).closest('tr').remove();
        calculateTotals();
    }

    // تحديث المجاميع عند تغيير الكمية أو السعر
    $(document).on('input', '.quantity, .price', function () {
        const row = $(this).closest('tr');
        const quantity = parseFloat(row.find('.quantity').val()) || 0;
        const price = parseFloat(row.find('.price').val()) || 0;
        const subtotal = quantity * price;
        row.find('.subtotal').val(subtotal.toFixed(2));
        calculateTotals();
    });

    $('#discount').on('input', function () {
        calculateTotals();
    });

    function calculateTotals() {
        let total = 0;
        $('.subtotal').each(function () {
            total += parseFloat($(this).val()) || 0;
        });

        const discount = parseFloat($('#discount').val()) || 0;
        const net_total = total - discount;

        $('#total').val(total.toFixed(2));
        $('#net_total').val(net_total.toFixed(2));
    }

    // حساب المجاميع عند تحميل الصفحة
    $(document).ready(function() {
        calculateTotals();
    });
</script>
@endpush