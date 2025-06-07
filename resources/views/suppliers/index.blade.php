@extends('layouts.index')
@section('title', trans('suppliers Page'))
@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="">{{trans('suppliers')}}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                            <li class="breadcrumb-item active "> {{trans('suppliers table')}} </li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Card for the table -->
        <div class="card p-4">
            <div class="card-header">
                <h3 class="card-title">{{__('suppliers table')}}</h3>
                <div class="card-tools">

                    @if(!Auth::user()->hasRole('agent'))
                        <a class="btn btn-secondary btn-sm" href="{{ route('suppliers.trashed') }}">
                            <i class="fas fa-trash"></i> @lang('Trashed suppliers')
                        </a>
                    @endif

                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createSupplierModal" title="@lang('Add supplier')">
                        <i class="fas fa-user-plus"></i> @lang('add supplier')
                    </button>

                </div>
            </div>
            <!-- /.card-header -->

            <div class="table-responsive">
                <table id="supplier-table" class="table table-bordered table-hover w-100">
                    <thead>
                        <tr>
                            <th class="text-center">{{ trans('id') }}</th>
                            <th class="text-center">{{ trans('name') }}</th>
                            <th class="text-center">{{ trans('balance') }}</th>
                            <th class="text-center">{{ trans('phone') }}</th>
                            <th class="text-center">{{ trans('company name') }}</th>
                            <th class="text-center">{{ trans('store') }}</th>
                            <th class="text-center">{{ trans('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded dynamically here -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
    </div>

    @include('layouts.parts.modalsForSuppliers')
@endsection

@push('cssModal')
    <link rel="stylesheet" href="{{asset('dist/css/myCustomTable.css')}}">
@endpush

@push('jsModal')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    // إعداد CSRF token لجميع طلبات AJAX في jQuery
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const userIsNotAgent = @json(!Auth::user()->hasRole('agent'));

    // تهيئة جدول الموردين
    let table = $('#supplier-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('suppliers.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'balance', name: 'balance' },
            { data: 'phone', name: 'phone' },
            { data: 'company_name', name: 'company_name' },
            ...(userIsNotAgent ? [{ data: 'store', name: 'store' }] : []),
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        dom: '<"row d-flex align-items-center p-3"<"col-md-3 col-12"l><"col-md-6 col-12 text-md-end text-center"B><"col-md-3 col-12"f>>' +
             '<"row"<"col-md-12"t>>' +
             '<"row"<"col-md-6"i><"col-md-6"p>>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '{{ trans("export_to_excel") }}',
                className: 'btn btn-success btn-sm',
                exportOptions: { columns: [1, 2] }
            }
        ],
        lengthMenu: [10, 25, 50, 100],
        language: {
            lengthMenu: "{{ trans('Show') }} _MENU_ {{ trans('entries') }}",
            info: "{{ trans('Showing') }} _START_ {{ trans('to') }} _END_ {{ trans('of') }} _TOTAL_ {{ trans('entries') }}",
            search: "",
            searchPlaceholder: "{{ trans('Search...') }}",
            paginate: {
                first: "{{ trans('First') }}",
                last: "{{ trans('Last') }}",
                next: "{{ trans('Next') }}",
                previous: "{{ trans('Previous') }}"
            }
        }
    });

    // إرسال نموذج إنشاء مورد جديد
    $('#createSupplierForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitButton = form.find('button[type="submit"]');

        // تنظيف الأخطاء السابقة
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: response.status,
                    title: response.title,
                    text: response.message,
                    timer: response.timer,
                    showConfirmButton: false,
                    timerProgressBar: true
                });

                if(response.status === 'success') {
                    form[0].reset();
                    table.ajax.reload();
                    $('#createSupplierModal').modal('hide');
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON?.message || 'حدث خطأ غير متوقع';
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: errorMessage,
                    timer: 5000,
                    showConfirmButton: true
                });

                if(xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}-error`).text(value[0]);
                    });
                }
            },
            complete: function() {
                submitButton.prop('disabled', false).html('<i class="fas fa-save"></i> حفظ');
            }
        });
    });

    // تعريف مودال تعديل المورد مرة واحدة
    const editSupplierModalEl = document.getElementById('editSupplierModal');
    const editSupplierModal = new bootstrap.Modal(editSupplierModalEl);

    // تنظيف الأخطاء عند فتح مودال التعديل
    editSupplierModalEl.addEventListener('show.bs.modal', () => {
        document.getElementById('editSupplierErrors').classList.add('d-none');
        document.getElementById('editSupplierErrorsList').innerHTML = '';
        $('#editSupplierForm')[0].reset();
        $('#editSupplierForm').find('.is-invalid').removeClass('is-invalid');
    });

    // إدارة الأحداث على الأزرار باستخدام مستمع حدث واحد
    document.addEventListener('click', function(e) {
        // عرض بيانات مورد
        const viewBtn = e.target.closest('.view-supplier-btn');
        if (viewBtn) {
            const url = viewBtn.getAttribute('data-url');

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view-supplier-name').textContent = data.name;
                    document.getElementById('view-supplier-company').textContent = data.company_name;
                    document.getElementById('view-supplier-phone').textContent = data.phone;
                    document.getElementById('view-supplier-address').textContent = data.address;
                    document.getElementById('view-supplier-store').textContent = data.store?.name || '—';

                    // تنسيق التواريخ بالعربية
                    const createdDate = new Date(data.created_at).toLocaleString('ar-EG');
                    const updatedDate = new Date(data.updated_at).toLocaleString('ar-EG');

                    document.getElementById('view-supplier-created').textContent = createdDate;
                    document.getElementById('view-supplier-updated').textContent = updatedDate;

                    const modal = new bootstrap.Modal(document.getElementById('viewSupplierModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('عرض المورد فشل:', error);
                    Swal.fire('خطأ', 'فشل في تحميل البيانات', 'error');
                });
            return;
        }

        // فتح مودال تعديل مورد وتعبئة البيانات
        const editBtn = e.target.closest('.edit-supplier-btn');
        if (editBtn) {
            $('#edit-supplier-id').val(editBtn.getAttribute('data-id'));
            $('#edit-supplier-name').val(editBtn.getAttribute('data-name'));
            $('#edit-supplier-company').val(editBtn.getAttribute('data-company'));
            $('#edit-supplier-phone').val(editBtn.getAttribute('data-phone'));
            $('#edit-supplier-address').val(editBtn.getAttribute('data-address'));
            $('#edit-supplier-store').val(editBtn.getAttribute('data-store'));

            $('#editSupplierForm').attr('action', 'suppliers/' + editBtn.getAttribute('data-id'));

            editSupplierModal.show();
            return;
        }
    });

    // حفظ تعديل المورد
    $('#editSupplierForm').on('submit', function(e) {
        e.preventDefault();

        const id = $('#edit-supplier-id').val();
        const formData = new FormData(this);
        // أضف _method=PUT لتوافق مع Laravel
        formData.append('_method', 'PUT');

        // تنظيف الأخطاء السابقة
        $('#editSupplierForm').find('.is-invalid').removeClass('is-invalid');
        $('#editSupplierErrors').addClass('d-none');
        $('#editSupplierErrorsList').empty();

        fetch(`suppliers/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'تم التحديث!',
                    text: data.message,
                    confirmButtonText: 'موافق'
                }).then(() => {
                    editSupplierModal.hide();
                    table.ajax.reload(null, false);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: data.message || 'حدث خطأ غير متوقع',
                });
            }
        })
        .catch(async error => {
            let errorMsg = 'حدث خطأ أثناء الاتصال بالخادم.';
            if (error.json) {
                const err = await error.json();
                if (err.errors) {
                    // عرض أخطاء التحقق في المودال
                    showEditSupplierErrors(err.errors);

                    // إضافة class is-invalid لكل حقل فيه خطأ
                    Object.keys(err.errors).forEach(key => {
                        $(`#edit-${key}`).addClass('is-invalid');
                        $(`#edit-${key}-error`).text(err.errors[key][0]);
                    });
                }
                errorMsg = err.message || errorMsg;
            }
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: errorMsg,
            });
        });
    });

    // حذف مورد مع تأكيد باستخدام SweetAlert2
    $(document).on('click', '.delete-supplier', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: '{{ trans("Are you sure?") }}',
            text: "{{ trans("You won't be able to revert this!") }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ trans("Yes, delete it") }}',
            cancelButtonText: '{{ trans("Cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('{{ trans("Deleted") }}!', response.message, 'success');
                            table.ajax.reload();
                        }
                    },
                    error: function() {
                        Swal.fire('{{ trans("Error") }}!', '{{ trans("Something went wrong") }}', 'error');
                    }
                });
            }
        });
    });

    // إزالة أخطاء التحقق عند إغلاق المودالات
    $('#editSupplierModal, #createSupplierModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').text('');
        if (this.id === 'editSupplierModal') {
            $('#editSupplierErrors').addClass('d-none');
            $('#editSupplierErrorsList').empty();
        }
    });

    // دالة عرض أخطاء التحقق في مودال التعديل
    function showEditSupplierErrors(errors) {
        const errorDiv = document.getElementById('editSupplierErrors');
        const errorList = document.getElementById('editSupplierErrorsList');

        errorList.innerHTML = '';

        for (let key in errors) {
            if (errors.hasOwnProperty(key)) {
                errors[key].forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorList.appendChild(li);
                });
            }
        }

        errorDiv.classList.remove('d-none');
    }

    // إخفاء رسالة الأخطاء عند تعديل الفورم في التعديل
    $('#editSupplierForm').on('input', function () {
        $('#editSupplierErrors').addClass('d-none');
    });

    // عرض رسالة نجاح من الجلسة (Blade)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '{{ trans("Success") }}',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush
