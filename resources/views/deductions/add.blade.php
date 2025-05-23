@extends('layouts.index')

@section('title', $pageTitle . ' PAGE')

@section('breadcramp')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-sm-6">
                    <h1>{{ trans('invoice') }}</h1>
                </div>
                <div class="col-sm-6">
                    @parent
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                        <li class="breadcrumb-item active"> {{ trans('invoice table') }} </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title"><i class="fas fa-file-invoice"></i> Add Deduction</h3>
    </div>
    <div class="card-body">
        <div class="btn-group w-100 mb-4">
            <button class="btn btn-primary active" onclick="showForm('general')">خصم عام</button>
            <button class="btn btn-warning" onclick="showForm('invoice')">خصم على فاتورة</button>
            <button class="btn btn-info" onclick="showForm('per_piece')">خصم عدد قطع</button>
        </div>

        <!-- الخصم العام -->
        <form id="form-general" class="discount-form" method="POST" action="{{ route('deductions.store') }}">
            @csrf
            <div class="row mb-4">
                @if(!Auth::user()->hasRole('agent'))
                    <div class="col-md-6">
                        <label><strong>المتجر</strong></label>
                        <select class="form-control" name="store_id" required>
                            <option value="">اختر المتجر</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-6">
                    <label><strong>العميل</strong></label>
                    <select class="form-control" name="client_id" required>
                        <option value="">اختر العميل</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label><strong>رقم الإذن (اختياري)</strong></label>
                    <input type="number" name="voucher_no" class="form-control" placeholder="">
                </div>
                <div class="col-md-6">
                    <label><strong>قيمة الخصم</strong></label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>
            </div>

            <div class="form-group mb-4">
                <label><strong>سبب الخصم</strong></label>
                <textarea name="reason" class="form-control" rows="3" placeholder="مثال: خصم مقابل مرتجع..."></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save me-1"></i> حفظ الخصم
                </button>
            </div>
        </form>

        <!-- خصم على فاتورة -->
        <form id="form-invoice" class="discount-form d-none">
            <h5>خصم على موديلات</h5>
            <div class="form-group">
                <label>الموديلات</label>
                <select name="product" class="form-control" required>
                    <option value="">{{ trans('Deductions') }}</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"><label>عدد الأصناف</label><input type="number" name="items_count" class="form-control"></div>
            <div class="form-group"><label>الكمية</label><input type="number" name="quantity" class="form-control"></div>
            <div class="form-group"><label>قيمة الخصم</label><input type="number" name="amount" class="form-control"></div>
            <div class="form-group"><label>إجمالي جزئي</label><input type="number" name="subtotal" class="form-control" readonly></div>
            <div class="form-group"><label>الإجمالي الكلي</label><input type="number" name="total" class="form-control" readonly></div>
        </form>

        <!-- خصم حسب عدد القطع -->
        <form id="form-per_piece" class="discount-form d-none">
            <h5>خصم حسب عدد القطع</h5>
            <div class="form-group">
                <label>الموديلات</label>
                <select class="form-control" name="models[]" multiple>
                    @foreach($products as $model)
                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"><label>عدد القطع</label><input type="number" name="pieces" class="form-control" oninput="calculateTotalPieceDiscount()"></div>
            <div class="form-group"><label>قيمة الخصم لكل قطعة</label><input type="number" name="discount_per_piece" class="form-control" oninput="calculateTotalPieceDiscount()"></div>
            <div class="form-group"><label>الإجمالي</label><input type="number" id="piece_total" class="form-control" readonly></div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // افتراضي عرض نموذج الخصم العام
    document.getElementById('form-general').classList.remove('d-none');
    document.querySelector('.btn-group .btn-primary').classList.add('active');

    // عرض SweetAlert إذا كانت هناك رسالة نجاح
    @if(session()->has('success'))
        Swal.fire({
            icon: 'success',
            title: 'تم بنجاح!',
            text: '{{ session('success') }}',
            confirmButtonText: 'حسنًا'
        });
    @elseif(session()->has('error'))
        Swal.fire({
            icon: 'error',
            title: 'حدث خطأ!',
            text: '{{ session('error') }}',
            confirmButtonText: 'موافق'
        });
    @endif
});

function showForm(type) {
    document.querySelectorAll('.discount-form').forEach(form => form.classList.add('d-none'));
    document.getElementById('form-' + type).classList.remove('d-none');

    document.querySelectorAll('.btn-group button').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
}

function calculateTotalPieceDiscount() {
    const pieces = parseFloat(document.querySelector('[name="pieces"]').value || 0);
    const discountPerPiece = parseFloat(document.querySelector('[name="discount_per_piece"]').value || 0);
    const total = pieces * discountPerPiece;
    document.getElementById('piece_total').value = total.toFixed(2);
}
</script>

<style>
.discount-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.btn-group .btn {
    margin-right: 5px;
}
.btn.active {
    font-weight: bold;
    box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
}
</style>
@endsection
