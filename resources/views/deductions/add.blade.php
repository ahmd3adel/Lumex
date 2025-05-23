@extends('layouts.index')

@section('title', $pageTitle . ' PAGE')

@section('breadcramp')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>{{ trans('invoice') }}</h1>
                </div>
                <div class="col-sm-6">
                    @parent
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                        <li class="breadcrumb-item active">{{ trans('invoice table') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title"><i class="fas fa-file-invoice"></i> إضافة خصم جديد</h3>
    </div>
    <div class="card-body">
        <form id="discount-form" method="POST" action="{{ route('deductions.store') }}">
            @csrf
            
            <div class="row mb-3">
                @if(!Auth::user()->hasRole('agent'))
                <div class="col-md-6">
                    <label class="form-label"><strong>اختر المتجر</strong></label>
                    <select name="store_id" id="store_id" class="form-control" required>
                        <option value="">اختر المتجر</option>
                        @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="col-md-6">
                    <label class="form-label"><strong>اختر العميل</strong></label>
                    <select name="client_id" id="client_id" class="form-control" required>
                        <option value="">اختر العميل</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label"><strong>رقم الإذن (اختياري)</strong></label>
                    <input type="number" name="voucher_no" class="form-control" placeholder="رقم الإذن">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label"><strong>قيمة الخصم</strong></label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                        <span class="input-group-text">ر.س</span>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label"><strong>تاريخ الخصم</strong></label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div id="invoice-fields" class="row mb-3 d-none">
                <div class="col-md-6">
                    <label class="form-label"><strong>رقم الفاتورة</strong></label>
                    <input type="text" name="invoice_no" class="form-control" placeholder="رقم الفاتورة">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><strong>تاريخ الفاتورة</strong></label>
                    <input type="date" name="invoice_date" class="form-control">
                </div>
            </div>

            <div id="product-fields" class="row mb-3 d-none">
                <div class="col-md-6">
                    <label class="form-label"><strong>اختر المنتج</strong></label>
                    <select name="product_id" class="form-control">
                        <option value="">اختر المنتج</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><strong>الكمية</strong></label>
                    <input type="number" name="product_qty" class="form-control" min="1" value="1">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>سبب الخصم</strong></label>
                <textarea name="reason" class="form-control" rows="3" required placeholder="سبب الخصم..."></textarea>
            </div>

            <div class="text-end mt-4">
                <button type="reset" class="btn btn-secondary mx-2">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ الخصم</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@push('scripts')
    

<script>
$(document).ready(function() {
  
    // تحديث قائمة العملاء عند تغيير المتجر
    $('#store_id').on('change', function() {
        var storeId = $(this).val();
        if (storeId) {
            $.ajax({
                url: '{{ route("get.clients.by.store") }}',
                type: 'GET',
                data: { store_id: storeId },
                success: function(response) {
                    var clientSelect = $('#client_id');
                    clientSelect.empty();
                    clientSelect.append('<option value="">اختر العميل</option>');
                    $.each(response.clients, function(key, client) {
                        clientSelect.append('<option value="' + client.id + '">' + client.name + '</option>');
                    });
                },
                error: function() {
                    alert('حدث خطأ أثناء تحميل العملاء.');
                }
            });
        } else {
            $('#client_id').html('<option value="">اختر العميل</option>');
        }
    });

    // إظهار/إخفاء الحقول حسب نوع الخصم
    $('select[name="discount_type"]').on('change', function() {
        var type = $(this).val();
        
        $('#invoice-fields, #product-fields').addClass('d-none');
        
        if (type === 'invoice') {
            $('#invoice-fields').removeClass('d-none');
        } else if (type === 'product') {
            $('#product-fields').removeClass('d-none');
        }
    });

    // التحقق من صحة النموذج قبل الإرسال
    $('#discount-form').on('submit', function(e) {
        var amount = $('input[name="amount"]').val();
        if (amount <= 0) {
            alert('قيمة الخصم يجب أن تكون أكبر من الصفر');
            e.preventDefault();
        }
    });
});
</script>
@endpush
<style>
.d-none {
    display: none !important;
}
.form-label {
    font-weight: bold;
    margin-bottom: 5px;
}
.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}
</style>
@endsection