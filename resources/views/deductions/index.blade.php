@extends('layouts.index')

@section('title', $pageTitle . ' PAGE')

@push('style')
<style>
    .custom-modal-width {
        width: 100%;
        max-width: 1000px;
        margin: auto;
    }
</style>
@endpush

@section('breadcramp')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>{{ __('deductions') }}</h1>
                </div>
                <div class="col-sm-6">
                    @parent
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('deduction table') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card p-4">
        <div class="card-header">
            <h3 class="card-title">{{ __('deduction table') }}</h3>
<div class="card-tools">
    <a class="btn btn-primary" href="{{ route('deductions.create') }}">
        <i class="fas fa-plus-circle"></i> @lang('add deduction')
    </a>
</div>


        </div>

        <div class="table-responsive">
            <table id="deduction-table" class="table table-bordered table-hover w-100">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>رقم السند</th>
                        <th>العميل</th>
                        <th>الشركة</th>
                        <th>المبلغ</th>
                        <th>تاريخ الخصم</th>
                        <th>{{ trans('actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deductionModal" tabindex="-1" role="dialog" aria-labelledby="deductionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('deductions.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deductionModalLabel">@lang('Add Deduction')</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{-- نوع الخصم --}}
          <div class="form-group">
            <label>نوع الخصم</label>
            <select name="discount_type" class="form-control" required>
              <option value="invoice">خصم على الفاتورة كاملة</option>
              <option value="per_item">خصم على كل صنف</option>
              <option value="quantity_based">خصم حسب عدد القطع</option>
              <option value="custom">خصم يدوي</option>
            </select>
          </div>

          {{-- قيمة الخصم --}}
          <div class="form-group">
            <label>قيمة الخصم</label>
            <input type="number" name="discount_value" class="form-control" step="0.01" required>
          </div>

          {{-- السبب --}}
          <div class="form-group">
            <label>السبب (اختياري)</label>
            <input type="text" name="reason" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">حفظ</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
        </div>
      </div>
    </form>
  </div>
</div>


@include('layouts.parts.modalsForinvoices') {{-- عدل هذا إذا كانت هناك مودالات مخصصة للخصومات --}}
@endsection

@push('cssModal')
<link rel="stylesheet" href="{{ asset('dist/css/myCustomTable.css') }}">
@endpush

@push('jsModal')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        const userIsNotAgent = {{ Auth::user()->hasRole('agent') ? 'false' : 'true' }};

        $('#deduction-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('deductions.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'voucher_no', name: 'voucher_no' },
                { data: 'client', name: 'client' },
                { data: 'store', name: 'store' },
                { data: 'amount', name: 'amount' },
                { data: 'receipt_date', name: 'receipt_date' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ],
            columnDefs: [
                {
                    targets: [3], // العميل
                    visible: userIsNotAgent,
                }
            ],
            dom: '<"row d-flex align-items-center p-3"<"col-md-3 col-12"l><"col-md-6 col-12 text-md-end text-center"B><"col-md-3 col-12"f>>' +
                 '<"row"<"col-md-12"t>>' +
                 '<"row"<"col-md-6"i><"col-md-6"p>>',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: 'تصدير PDF',
                    className: 'btn btn-danger btn-sm',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function (doc) {
                        doc.content.splice(0, 0, {
                            text: 'تقرير الخصومات',
                            style: 'header',
                            alignment: 'center',
                            fontSize: 18,
                            margin: [0, 0, 0, 20]
                        });
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'تصدير Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                }
            ],
            lengthMenu: [10, 25, 50, 100],
            language: {
                lengthMenu: "عرض _MENU_ سجلات",
                info: "عرض _START_ إلى _END_ من أصل _TOTAL_ سجل",
                search: "_INPUT_",
                searchPlaceholder: "بحث في الخصومات...",
                paginate: {
                    first: "الأول",
                    last: "الأخير",
                    next: "التالي",
                    previous: "السابق"
                }
            },
            responsive: true
        });
    });
</script>
@endpush
