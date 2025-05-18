@extends('layouts.index')
@section('title', $pageTitle . ' PAGE')

@section('breadcramp')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('invoice') }}</h1>
                </div>
                <div class="col-sm-6 text-sm-end">
                    @parent
                    <ol class="breadcrumb float-sm-right d-print-none">
                        <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('invoice table') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<style>
    body {
        background-color: #f9f9f9;
    }

    .table thead {
        background-color: #37474f;
        color: #fff;
    }

    .invoice-info h5 {
        font-size: 1.25rem;
        color: #444;
    }

    .dt-buttons .btn {
        margin-left: 5px;
    }

    @media print {
        .d-print-none {
            display: none !important;
        }

        body {
            background: white;
        }

        .shadow,
        .shadow-sm {
            box-shadow: none !important;
        }

        .container,
        .content-wrapper {
            width: 100% !important;
            margin: 0;
            padding: 0;
        }

        .card,
        .rounded {
            border: none !important;
        }

        table {
            font-size: 12px;
        }

        h4,
        h5 {
            font-size: 16px;
        }
    }
</style>

<section class="content">
    <div class="container-fluid">

        <div class="text-center mb-4">
            <h4><strong>فاتورة رقم: {{ $inv->invoice_no }}</strong></h4>
        </div>

        <div class="card border-0 shadow p-4">

            <div class="row invoice-info mb-4 g-3">
                <!-- من -->
                <div class="col-md-4">
                    <div class="p-4 rounded shadow-sm h-100" style="background-color: #e3f2fd; color: #0d47a1;">
                        <h5 class="mb-3"><strong>من:</strong></h5>
                        <address class="mb-0">
                            <strong>{{ $inv->store->name }}</strong><br>
                            {{ $inv->store->location }}<br>
                            هاتف: (804) 123-5432
                        </address>
                    </div>
                </div>

                <!-- إلى -->
                <div class="col-md-4">
                    <div class="p-4 rounded shadow-sm h-100" style="background-color: #fce4ec; color: #880e4f;">
                        <h5 class="mb-3"><strong>إلى:</strong></h5>
                        <address class="mb-0">
                            <strong>{{ $inv->client->name }}</strong><br>
                            {{ $inv->client->address ?? 'العنوان غير متوفر' }}
                        </address>
                    </div>
                </div>

                <!-- تفاصيل -->
                <div class="col-md-4">
                    <div class="p-4 rounded shadow-sm h-100" style="background-color: #e8f5e9; color: #1b5e20;">
                        <h5 class="mb-3"><strong>تفاصيل:</strong></h5>
                        <address class="mb-0">
                            <b>تاريخ الفاتورة:</b> {{ $inv->invoice_date }}<br>
                            <b>عدد القطع:</b> {{ $inv->pieces_no }}<br>
                            <b>الإجمالي:</b> {{ number_format($inv->net_total, 0) }}
                        </address>
                    </div>
                </div>
            </div>

            <div class="mt-4">
<h4 class="mb-3">تفاصيل المنتجات</h4>
<div class="table-responsive">
    <table id="invoiceProductsTable" class="table table-bordered table-striped">
        <thead class="bg-dark text-white text-center">
            <tr >
                <th class="text-center">#</th>
                <th class="text-center"> اسم المنتج</th>
                <th class="text-center">الكمية</th>
                <th class="text-center">السعر</th>
                <th class="text-center">السعر الجزئي</th>
            </tr>
        </thead>
    </table>
</div>

                <div class="mt-3 text-end">
<div class="row justify-content-end mt-4">
    <div class="col-md-6">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th style="width:50%">الإجمالي بدون خصم:</th>
                    <td>{{ number_format($inv->total, 0) }} جنيه</td>
                </tr>
                <tr>
                    <th>إجمالي الخصومات:</th>
                    <td>{{ number_format($inv->discount, 0) }} جنيه</td>
                </tr>
                <tr>
                    <th><strong>الصافي (الإجمالي الكلي):</strong></th>
                    <td><strong>{{ number_format($inv->net_total, 0) }} جنيه</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
            </div>
        </div>

        <div class="text-center mt-4 d-print-none">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> طباعة
            </button>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
$('#invoiceProductsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('invoices.show', $inv->id) }}',
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'product_name', name: 'product.name' },
        { data: 'quantity', name: 'quantity' },
        { data: 'price', name: 'price' },
        
        { data: 'subtotal', name: 'subtotal' }
    ],
    searching: false,
    paging: false,
    info: false,
    ordering: false,
    language: {
        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
    }
});

    });
</script>
@endpush
