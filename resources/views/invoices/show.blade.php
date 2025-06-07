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
        .d-print-none,
        .payment-section {
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
    
    /* تنسيقات بوابات الدفع */
    .payment-methods {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 20px;
    }
    
    .payment-method {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        flex: 1;
        min-width: 200px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .payment-method:hover {
        border-color: #4e73df;
        box-shadow: 0 0 10px rgba(78, 115, 223, 0.3);
    }
    
    .payment-method.active {
        border-color: #4e73df;
        background-color: #f8f9fe;
    }
    
    .payment-method img {
        max-height: 40px;
        margin-bottom: 10px;
    }
    
    .stripe-payment-form {
        display: none;
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #f9f9f9;
    }
    
    .payment-section {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #eee;
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
                
                <!-- قسم الدفع -->
                <div class="payment-section d-print-none">
                    <h4 class="mb-3">طرق الدفع المتاحة</h4>
                    
                    <div class="payment-methods">
                        <div class="payment-method active" data-method="cash">
                            <h5>الدفع نقداً</h5>
                            <p>الدفع عند الاستلام أو في المحل</p>
                        </div>
                        
                        <div class="payment-method" data-method="bank">
                            <h5>التحويل البنكي</h5>
                            <p>تحويل مباشر إلى الحساب البنكي</p>
                        </div>
                        
                        <div class="payment-method" data-method="stripe">
                            <img src="https://stripe.com/img/v3/home/twitter.png" alt="Stripe Logo">
                            <h5>الدفع بالبطاقة</h5>
                            <p>دفع آمن عبر Stripe</p>
                        </div>
                    </div>
                    
                    <!-- نموذج الدفع عبر Stripe -->
                    <div id="stripe-payment-form" class="stripe-payment-form">
                        <form id="payment-form" action="{{ route('stripe.pay') }}" method="POST">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{ $inv->id }}">
                            <input type="hidden" name="amount" value="500">
                            
                            <div class="form-group">
                                <label for="card-element">معلومات البطاقة الائتمانية</label>
                                <div id="card-element" class="form-control" style="height: 40px; padding: 10px;"></div>
                                <div id="card-errors" role="alert" class="text-danger"></div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mt-3"> دفع {{ number_format($inv->net_total, 0) }} جنيه
                            </button>
                        </form>
                    </div>
                    
                    <!-- تفاصيل التحويل البنكي -->
                    <div id="bank-payment-form" class="stripe-payment-form" style="display: none;">
                        <h5>معلومات الحساب البنكي</h5>
                        <div class="bank-details">
                            <p><strong>اسم البنك:</strong> البنك الأهلي التجاري</p>
                            <p><strong>اسم الحساب:</strong> {{ $inv->store->name }}</p>
                            <p><strong>رقم الحساب:</strong> SA03 8000 1234 5678 9012 3456</p>
                            <p><strong>IBAN:</strong> SA03 8000 1234 5678 9012 3456</p>
                        </div>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> الرجاء إرسال صورة من إيصال التحويل بعد إتمام العملية
                        </div>
                    </div>
                    
                    <!-- تأكيد الدفع نقداً -->
                    <div id="cash-payment-form" class="stripe-payment-form">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> يمكنك دفع المبلغ نقداً عند الاستلام أو في المحل
                        </div>
                        <form action="" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success mt-3">
                                <i class="fas fa-check"></i> تأكيد الدفع نقداً
                            </button>
                        </form>
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
<script src="https://js.stripe.com/v3/"></script>
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
        
        // اختيار طريقة الدفع
        $('.payment-method').click(function() {
            $('.payment-method').removeClass('active');
            $(this).addClass('active');
            
            const method = $(this).data('method');
            $('.stripe-payment-form').hide();
            
            if (method === 'stripe') {
                $('#stripe-payment-form').show();
                initializeStripe();
            } else if (method === 'bank') {
                $('#bank-payment-form').show();
            } else {
                $('#cash-payment-form').show();
            }
        });
        
        // تهيئة Stripe
        function initializeStripe() {
            const stripe = Stripe('{{ env("STRIPE_KEY") }}');
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                },
                hidePostalCode: true
            });
            
            cardElement.mount('#card-element');
            
            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                
                const {error, paymentMethod} = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });
                
                if (error) {
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                } else {
                    // أضف paymentMethod.id إلى النموذج وإرساله
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'payment_method');
                    hiddenInput.setAttribute('value', paymentMethod.id);
                    form.appendChild(hiddenInput);
                    
                    // إرسال النموذج
                    form.submit();
                }
            });
        }
    });
</script>
@endpush