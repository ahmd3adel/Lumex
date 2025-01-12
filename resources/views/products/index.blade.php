@extends('layouts.index')
@section('title' , $pageTitle)
@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="">{{trans('Products')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                            <li class="breadcrumb-item active "> {{trans('products table')}} </li>
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
                        <h3 class="card-title">{{__('products table')}}</h3>
                        <div class="card-tools">
                            <a class="btn btn-secondary btn-sm"  href="{{ route('products.trashed') }}">
                                <i class="fas fa-trash"></i> @lang('Trashed products')
                            </a>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createproductModal" aria-controls="createproductModal" title="@lang('Add product')">
                                <i class="fas fa-user-plus"></i> @lang('add product')
                            </button>

                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="table-responsive ">
                        <table id="product-table" class="table table-bordered table-hover w-100">
                            <thead>
                            <tr>

                                <th><i class="fas fa-hashtag"></i> {{ trans('id') }}</th>
                                <th><i class="fas fa-user"></i> {{ trans('name') }}</th>
                                <th><i class="fas fa-phone"></i> {{ trans('description') }}</th>
                                <th><i class="fas fa-user"></i> {{ trans('price') }}</th>
                                <th><i class="fas fa-user"></i> {{ trans('quantity') }}</th>
                                <th><i class="fas fa-user"></i> {{ trans('Cutter') }}</th>
                                <th><i class="fas fa-user"></i> {{ trans('Status') }}</th>
                                <th><i class="fas fa-user"></i> {{ trans('Store') }}</th>
                                <th><i class="fas fa-cogs"></i> {{ trans('actions') }}</th>


                            </tr>
                            </thead>
                        </table>
                    </div>



                </div>
                <!-- /.card -->
            </div>

            @include('layouts.parts.modalsForproducts')

        @endsection

        @push('cssModal')
            <link rel="stylesheet" href="{{asset('dist/css/myCustomTable.css')}}">
        @endpush
@push('jsModal')

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>



                $(document).ready(function () {
                    var userRole = "{{$userRole}}"
                    // إعداد الجدول باستخدام DataTables
                    let table = $('#product-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('products.index') }}",
                        columns: [
                            { data: 'id', name: 'id' },
                            { data: 'name', name: 'name' },
                            { data: 'description', name: 'description' },
                            { data: 'price', name: 'price' },
                            { data: 'quantity', name: 'quantity' },
                            { data: 'cutter_name', name: 'Cutter' },
                            { data: 'status', name: 'status' },
                            { data: 'store', name: 'store' , visible: userRole != 'agent'},
                            { data: 'action', name: 'action', orderable: false, searchable: false }
                        ],
                        dom: '<"row d-flex align-items-center p-3"<"col-md-3 col-12"l><"col-md-6 col-12 text-md-end text-center"B><"col-md-3 col-12"f>>' +
                            '<"row"<"col-md-12"t>>' +
                            '<"row"<"col-md-6"i><"col-md-6"p>>',
                        buttons: [
                            {
                                extend: 'pdfHtml5',
                                text: '{{ trans("export_to_pdf") }}',
                                className: 'btn btn-danger btn-sm',
                                orientation: 'portrait',
                                pageSize: 'A4',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                },
                                customize: function (doc) {
                                    doc.content.splice(0, 0, {
                                        text: 'product Report',
                                        style: 'header',
                                        alignment: 'center',
                                        fontSize: 18,
                                        margin: [0, 0, 0, 20]
                                    });
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                text: '{{ trans("export_to_excel") }}',
                                className: 'btn btn-success btn-sm',
                                exportOptions: {
                                    columns: [0, 1, 2, 3]
                                }
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
                    //view-product modal
                    document.addEventListener('click', function (e) {
                        if (e.target.closest('.view-product')) {
                            var id = e.target.getAttribute('data-id')
                            var name = e.target.getAttribute('data-name')
                            var price = e.target.getAttribute('data-price')
                            var quantity = e.target.getAttribute('data-quantity')
                            var cutter_name = e.target.getAttribute('data-cutter_name')


                            document.getElementById('modal-product-name').innerText = name
                            document.getElementById('modal-product-price').innerText = price
                            document.getElementById('modal-product-quantity').innerText = quantity
                            document.getElementById('cutter_name').innerText = cutter_name


                            var modal = document.getElementById('productModal');
                            var bootstrapModal = new bootstrap.Modal(modal);

                            bootstrapModal.show();
                        }
                    });

                    $(document).on('click', '.toggle-status', function () {
                        let userId = $(this).data('id');
                        $.ajax({
                            url: "{{ route('products.toggleStatus') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: userId
                            },
                            success: function (response) {
                                Swal.fire('{{ trans('Success') }}', '{{ trans('User status updated successfully.') }}', 'success');
                                table.ajax.reload();
                            },
                            error: function () {
                                Swal.fire('خطأ', 'حدث خطأ أثناء تحديث الحالة', 'error');
                            }
                        });
                    });

                    // Create product Form Submission
                    const storeproductRoute = "{{ route('products.store') }}";

                    document.getElementById('createproductForm').addEventListener('submit', function (event) {
                        event.preventDefault();
                        const form = this;
                        const submitButton = form.querySelector('.submit-creating-form');
                        const formData = new FormData(form);

                        const data = {};
                        formData.forEach((value, key) => {
                            data[key] = value;
                        });

                        disableButton(submitButton, true);

                        fetch(storeproductRoute, {
                            method: 'POST',
                            body: JSON.stringify(data),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(errorData => {
                                        throw errorData;
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    $('#createproductModal').modal('hide').removeClass('show').addClass('fade');
                                    $('body').removeClass('modal-open');
                                    $('body').css('padding-right', '');
                                    $('.modal-backdrop').remove();

                                    Swal.fire('Success', 'product created successfully.', 'success');
                                    form.reset();
                                    table.ajax.reload();
                                } else {
                                    handleValidationErrors(form, data.errors);
                                }
                            })
                            .catch(error => {
                                if (error.errors) {
                                    handleValidationErrors(form, error.errors);
                                } else {
                                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                                }
                            })
                            .finally(() => disableButton(submitButton, false));
                    });



                    //edit-product modal
                    document.addEventListener('click' , function (e) {
                        if(e.target.closest('.edit-product')){

                            var id = e.target.getAttribute('data-id')
                            var name = e.target.getAttribute('data-name')
                            var price = e.target.getAttribute('data-price')
                            var quantity = e.target.getAttribute('data-quantity')
                            var cutter_name = e.target.getAttribute('data-cutter_name')
                            var description = e.target.getAttribute('data-description')

                            document.getElementById('edit-id').value = id
                            document.getElementById('edit-name').value = name
                            document.getElementById('edit-cutter_name').value = cutter_name
                            document.getElementById('edit-price').value = price
                            document.getElementById('edit-quantity').value = quantity
                            document.getElementById('edit-description').value = description


                            var modal = document.getElementById('editproductModal');
                            var bootstrapModal = new bootstrap.Modal(modal);
                            bootstrapModal.show();
                        }
                    })

                    document.getElementById('editproductForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        var form = this;
                        const formData = new FormData(form);

                        // تحويل FormData إلى JSON
                        const data = {};
                        formData.forEach((value, key) => {
                            data[key] = value;
                        });

                        // استبدال :id في الرابط
                        const updateProductRoute = "{{ route('products.update', ':id') }}";
                        const finalRoute = updateProductRoute.replace(':id', data.id);

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
                                        console.error("Validation Errors:", errorData); // عرض الأخطاء في الكونسول
                                        throw errorData; // إرسال الأخطاء إلى الكتلة catch
                                    });
                                }
                                return response.json(); // تحليل JSON إذا كانت الاستجابة ناجحة
                            })
                            .then((data) => {
                                if (data.success) {
                                    // تنفيذ عند النجاح
                                    $('#editproductModal').modal('hide').removeClass('show').addClass('fade');

                                    $('body').removeClass('modal-open'); // إزالة خاصية منع التمرير
                                    $('body').css('padding-right', ''); // إعادة ضبط الحشو إذا كان مضافًا
                                    $('.modal-backdrop').remove(); // إزالة الخلفية الداكنة
                                    Swal.fire('Success', 'product updated successfully.', 'success');
                                    table.ajax.reload(); // Reload DataTable
                                } else {
                                    console.error("Response Errors:", data.errors); // عرض الأخطاء في الكونسول
                                    handleValidationErrors(form, data.errors);
                                }
                            })
                            .catch((error) => {
                                // عرض الأخطاء العامة أو الأخطاء الناتجة من السيرفر
                                console.error("Unexpected Error:", error); // طباعة الخطأ في الكونسول

                                if (error.errors) {
                                    handleValidationErrors(form, error.errors);
                                } else {
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

                    // Confirmation before deleting a product
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
                    $('#createproductModal, #editproductModal').on('hidden.bs.modal', function () {
                        const form = this.querySelector('form');
                        if (form) resetForm(form);
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
