@extends('layouts.index')
@section('title', $pageTitle)

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
    /* تنسيقات الطباعة فقط */
    @media print {
        body, html {
            background: white !important;
            font-size: 12px !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .main-header,
        .main-sidebar,
        .content-header,
        .breadcrumb,
        .d-print-none {
            display: none !important;
        }
        
        .content-wrapper {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .print-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #000;
        }
        
        table {
            width: 100% !important;
            font-size: 12px !important;
        }
        
        .table th, .table td {
            padding: 5px !important;
            border-color: #ddd !important;
        }
        
        .invoice-info-box {
            page-break-inside: avoid;
        }
    }

    /* تنسيقات عامة متوافقة مع AdminLTE */
    .invoice-info-box {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="print-header d-print-none">
            <h3><strong>فاتورة رقم: {{ $inv->invoice_no }}</strong></h3>
        </div>

        <div class="card">

            
            <div class="card-body">
                <!-- ترويسة خاصة بالطباعة تظهر فقط عند الطباعة -->
<div class="d-print-block d-none mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <!-- اسم المصنع على اليمين -->
        <div class="text-end flex-grow-1">
            <h2 class="fw-bold m-0">مصنع: {{ Auth::user()->store->name }}</h2>
        </div>

        <!-- الشعار على اليسار -->
        <div class="text-start">
            <img src="{{ asset('logo.png') }}" alt="الشعار" style="max-width: 60px; height: auto;">
        </div>
    </div>

    <!-- اسم العميل في سطر مستقل -->
<div class="text-center mt-2">
    <h4 class="fw-bold border-top pt-2">
        العميل: {{ $inv->client->name }} <span class="mx-3">|</span> رقم الفاتورة: {{ $inv->invoice_no }}
    </h4>
</div>
</div>

                <div class="row invoice-info mb-4 g-3 d-print-none">

                    <!-- من -->
                    <div class="col-md-4 invoice-info-box" style="background-color: #e3f2fd; color: #0d47a1;">
                        <h5 class="mb-3"><strong>من:</strong></h5>
                        <address class="mb-0">
                            <strong>{{ $inv->store->name }}</strong><br>
                            {{ $inv->store->location }}<br>
                            هاتف: (804) 123-5432
                        </address>
                    </div>

                    <!-- إلى -->
                    <div class="col-md-4 invoice-info-box" style="background-color: #fce4ec; color: #880e4f;">
                        <h5 class="mb-3"><strong>إلى:</strong></h5>
                        <address class="mb-0">
                            <strong>{{ $inv->client->name }}</strong><br>
                            {{ $inv->client->address ?? 'العنوان غير متوفر' }}
                        </address>
                    </div>

                    <!-- تفاصيل -->
                    <div class="col-md-4 invoice-info-box" style="background-color: #e8f5e9; color: #1b5e20;">
                        <h5 class="mb-3"><strong>تفاصيل:</strong></h5>
                        <address class="mb-0">
                            <b>تاريخ الفاتورة:</b> {{ $inv->invoice_date }}<br>
                            <b>عدد القطع:</b> {{ $inv->pieces_no }}<br>
                            <b>الإجمالي:</b> {{ number_format($inv->net_total, 0) }} جنيه
                        </address>
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="mb-3">تفاصيل المنتجات</h4>
                    <div class="table-responsive">
                        <table id="invoiceProductsTable" class="table table-bordered table-striped">
                            <thead class="bg-dark text-white text-center">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">اسم المنتج</th>
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
            
            <!-- تذييل الطباعة -->
    <div class="footer-section d-print-block" style="display: none;">
        <div style="float:left; width:40%;">
            <p>توقيع المسؤول: ________________</p>
        </div>
        <div style="float:right; width:40%; text-align:right;">
            <p>توقيع العميل: ________________</p>
        </div>
        <div style="clear:both;"></div>
    </div>
        </div>

        <div class="text-center mt-4 d-print-none">
            <a href="{{ route('invoices.edit', $inv->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> تعديل الفاتورة
            </a>
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
            },
            dom: '<"top"f>rt<"bottom"lip><"clear">'
        });
    });
</script>
@endpush