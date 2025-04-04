@extends('layouts.index')
@section('title' , 'receiptS PAGE')
@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="">{{trans('receipts')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                            <li class="breadcrumb-item active "> {{trans('receipts table')}} </li>
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
                        <h3 class="card-title">{{__('receipts table')}}</h3>
                        <div class="card-tools">
                            @if(!Auth::user()->hasRole('agent'))
                                <a class="btn btn-secondary btn-sm"  href="{{ route('receipts.trashed') }}">
                                    <i class="fas fa-trash"></i> @lang('Trashed receipts')
                                </a>
                            @endif

                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createreceiptModal">
                                <i class="fas fa-receipt-plus"></i> @lang('add receipt')
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="table-responsive">
                        <table id="receipt-table" class="table table-bordered table-hover w-100">
                            <thead>

                            <tr>
                                <th><i class="fas fa-hashtag"></i> {{ trans('id') }}</th>
                                <th><i class="fas fa-receipt"></i> {{ trans('voucher_no') }}</th>
                                <th><i class="fas fa-envelope mx-5 text-center"></i> {{ trans('client') }}</th>
                                <th><i class="fas fa-at"></i> {{ trans('amount') }}</th>
                                <th><i class="fas fa-phone"></i> {{ trans('payment_method') }}</th>
                                <th><i class="fas fa-phone"></i> {{ trans('receipt_date') }}</th>
                                <th><i class="fas fa-phone"></i> {{ trans('created_by') }}</th>
                                <th><pre class="p-0 m-0">        <i class="fas fa-cogs"></i>{{ trans('actions') }}             </pre></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>



                </div>
                <!-- /.card -->
            </div>

        @include('layouts.parts.modalsForreceipts')

        @endsection

        @push('cssModal')
            <link rel="stylesheet" href="{{asset('dist/css/myCustomTable.css')}}">
        @endpush
@push('jsModal')

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>



                $(document).ready(function () {
                    // إعداد الجدول باستخدام DataTables
                    let table = $('#receipt-table').DataTable({
                        processing: true, // Show loading indicator
                        serverSide: true, // Enable server-side processing
                        ajax: "{{ route('receipts.index') }}", // Dynamic data route
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                            { data: 'voucher_no', name: 'voucher_no' },
                            { data: 'client_id', name: 'client_id' },
                            { data: 'amount', name: 'amount' },
                            { data: 'payment_method', name: 'payment_method' },
                            { data: 'receipt_date', name: 'receipt_date' },
                            { data: 'created_by', name: 'created_by' },
                            { data: 'actions', name: 'actions', orderable: false, searchable: false }

                        ],
                        dom: '<"row d-flex align-items-center p-3"<"col-md-3 col-12"l><"col-md-6 col-12 text-md-end text-center"B><"col-md-3 col-12"f>>' +
                            '<"row"<"col-md-12"t>>' + // Table
                            '<"row"<"col-md-6"i><"col-md-6"p>>', // Pagination and info
                        buttons: [
                            {
                                extend: 'pdfHtml5',
                                text: '{{trans("Export To PDF")}}',
                                className: 'btn btn-danger btn-sm',
                                orientation: 'portrait',
                                pageSize: 'A4',
                                exportOptions: {
                                    columns: [0, 1, 2, 3 , 4 , 5] // Exported columns
                                },
                                customize: function (doc) {
                                    doc.content.splice(0, 0, {
                                        text: 'receipt Report',
                                        style: 'header',
                                        alignment: 'center',
                                        fontSize: 18,
                                        margin: [0, 0, 0, 20]
                                    });
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                text: 'Export to Excel',
                                className: 'btn btn-success btn-sm',
                                exportOptions: {
                                    columns: [0, 1, 2, 3] // Exported columns
                                }
                            }
                        ],
                        lengthMenu: [10, 25, 50, 100], // Rows per page options
                        language: {
                            lengthMenu: "Show _MENU_ entries",
                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                            search: "",
                            searchPlaceholder: "Search...",
                            paginate: {
                                first: "First",
                                last: "Last",
                                next: "Next",
                                previous: "Previous"
                            }
                        },
                        pageLength:100
                    });

                    //view-receipt modal
                    document.addEventListener('click', function (e) {
                        if (e.target.closest('.view-receipt')) {
                            var id = e.target.getAttribute('data-id')
                            var name = e.target.getAttribute('data-name')
                            var receiptname = e.target.getAttribute('data-receiptname')
                            var phone = e.target.getAttribute('data-phone')
                            var role = e.target.getAttribute('data-role')
                            var email = e.target.getAttribute('data-email')
                            var  joined= e.target.getAttribute('data-joined')

                            document.getElementById('modal-receipt-name').innerText = name
                            document.getElementById('modal-phone').innerText = phone
                            document.getElementById('modal-receipt-email').innerText = email
                            document.getElementById('modal-receipt-role').innerText = role
                            document.getElementById('modal-receipt-joined').innerText = joined
                            document.getElementById('modal-receiptname').innerText = receiptname

                            var modal = document.getElementById('receiptModal');
                            var bootstrapModal = new bootstrap.Modal(modal);

                            bootstrapModal.show();
                        }
                    });


                    {{--$(document).on('click', '.toggle-status', function () {--}}
                    {{--    let receiptId = $(this).data('id');--}}
                    {{--    $.ajax({--}}
                    {{--        url: "{{ route('receipts.toggleStatus') }}",--}}
                    {{--        type: 'POST',--}}
                    {{--        data: {--}}
                    {{--            _token: '{{ csrf_token() }}',--}}
                    {{--            id: receiptId--}}
                    {{--        },--}}
                    {{--        success: function (response) {--}}
                    {{--            Swal.fire('{{ trans('Success') }}', '{{ trans('receipt status updated successfully.') }}', 'success');--}}
                    {{--            table.ajax.reload();--}}
                    {{--        },--}}
                    {{--        error: function () {--}}
                    {{--            Swal.fire('خطأ', 'حدث خطأ أثناء تحديث الحالة', 'error');--}}
                    {{--        }--}}
                    {{--    });--}}
                    {{--});--}}

                    //edit-receipt modal
                    document.addEventListener('click' , function (e) {
                        if(e.target.closest('.edit-receipt')){
                            var receiptId = e.target.getAttribute('data-id');
                            var name = e.target.getAttribute('data-name')
                            var receiptname = e.target.getAttribute('data-receiptname')
                            var phone = e.target.getAttribute('data-phone')
                            var role = e.target.getAttribute('data-role')
                            var email = e.target.getAttribute('data-email')


                            document.getElementById('editId').value = receiptId
                            document.getElementById('edit-name').value = name
                            document.getElementById('edit-phone').value = phone
                            document.getElementById('edit-email').value = email
                            document.getElementById('edit-role').value = role
                            document.getElementById('edit-receiptname').value = receiptname
                            document.getElementById('editreceiptForm').setAttribute('action' , '/receipts/' + receiptId )
                            var modal = document.getElementById('editreceiptModal');
                            var bootstrapModal = new bootstrap.Modal(modal);
                            bootstrapModal.show();
                        }
                    })

                    document.getElementById('editreceiptForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        var form = this;
                        const formData = new FormData(form);

                        // تحويل FormData إلى JSON
                        const data = {};
                        formData.forEach((value, key) => {
                            data[key] = value;
                        });

                        // استبدال :id في الرابط
                        const updatereceiptRoute = "{{ route('receipts.update', ':id') }}";
                        const finalRoute = updatereceiptRoute.replace(':id', data.id);


                        fetch(finalRoute, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json', // نوع البيانات
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // CSRF Token
                            },
                            body: JSON.stringify(data), // تحويل البيانات إلى JSON
                        })
                            .then((response) => {
                                // تحقق من حالة الاستجابة
                                if (!response.ok) {
                                    return response.json().then((errorData) => {
                                        throw errorData; // إرسال الأخطاء إلى الكتلة catch
                                    });
                                }
                                return response.json(); // تحليل JSON إذا كانت الاستجابة ناجحة
                            })
                            .then((data) => {
                                if (data.success) {
                                    // تنفيذ عند النجاح
                                    $('#editreceiptModal').modal('hide').removeClass('show').addClass('fade');

                                    $('body').removeClass('modal-open'); // إزالة خاصية منع التمرير
                                    $('body').css('padding-right', ''); // إعادة ضبط الحشو إذا كان مضافًا
                                    $('.modal-backdrop').remove(); // إزالة الخلفية الداكنة
                                    Swal.fire('Success', 'receipt created successfully.', 'success');
                                    table.ajax.reload(); // Reload DataTable
                                } else {
                                    // تنفيذ عند الفشل
                                    handleValidationErrors(form, data.errors);
                                }

                            })
                            .catch((error) => {
                                if (error.errors) {
                                    // التعامل مع أخطاء التحقق
                                    handleValidationErrors(form, error.errors);
                                } else {
                                    // عرض رسالة خطأ عامة
                                    Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
                                }
                            });


                    });


                    // SweetAlert for success messages
                    @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    @endif

                    // Confirmation before deleting a receipt
                    $(document).on('submit', 'form.delete', function (e) {
                        e.preventDefault();
                        let form = this;
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "This action cannot be undone!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });

                    // Reset modal forms on close
                    $('#createreceiptModal, #editreceiptModal').on('hidden.bs.modal', function () {
                        const form = this.querySelector('form');
                        if (form) resetForm(form);
                    });

                    // Create receipt Form Submission
                    const storereceiptRoute = "{{ route('receipts.store') }}";
                    document.getElementById('createreceiptForm').addEventListener('submit', function (event) {
                        event.preventDefault();
                        const form = this;
                        const submitButton = form.querySelector('.submit-creating-form');
                        const formData = new FormData(form);

                        disableButton(submitButton, true);

                        form.querySelectorAll('.is-invalid').forEach(input => {
                            input.classList.remove('is-invalid');
                            const errorFeedback = input.nextElementSibling;
                            if (errorFeedback && errorFeedback.classList.contains('invalid-feedback')) {
                                errorFeedback.remove();
                            }
                        });

                        fetch(storereceiptRoute, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    disableButton(submitButton, true);
                                    $('#createreceiptModal').modal('hide');
                                    Swal.fire('Success', 'receipt created successfully.', 'success');
                                    table.ajax.reload(); // Reload DataTable
                                } else {
                                    handleValidationErrors(form, data.errors);
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error', 'An unexpected error occurred.', 'error');
                            })
                            .finally(() => disableButton(submitButton, false));
                    });

                    // store details modal
                    document.addEventListener('click', function (e) {
                        if (e.target.classList.contains('open-store-modal')) {
                            e.preventDefault();
                            const storeId = e.target.getAttribute('data-id');
                            fetch(`/receipts/store/${storeId}`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json'
                                },
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        document.getElementById('modal-store-name').innerText = data.data.name;
                                        document.getElementById('modal-store-location').innerText = data.data.location;
                                        // Show the modal
                                        const modal = new bootstrap.Modal(document.getElementById('storeModal'));
                                        modal.show();
                                    } else {
                                        alert('faild')
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    });



                });
                // Utility functions
                function resetForm(form) {
                    form.reset();
                    form.querySelectorAll('.is-invalid').forEach(input => input.classList.remove('is-invalid'));
                    form.querySelectorAll('.invalid-feedback').forEach(error => error.remove());
                }


                function handleValidationErrors(form, errors) {
                    for (let key in errors) {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            let errorFeedback = input.nextElementSibling || document.createElement('div');
                            errorFeedback.className = 'invalid-feedback';
                            errorFeedback.innerText = errors[key][0];
                            input.insertAdjacentElement('afterend', errorFeedback);
                        }
                    }
                }


                function disableButton(button, disable) {
                    if (disable) {
                        button.disabled = true;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                    } else {
                        button.disabled = false;
                        button.innerHTML = 'Submit';
                    }
                }





            </script>


    @endpush
