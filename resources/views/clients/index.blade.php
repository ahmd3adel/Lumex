@extends('layouts.index')
@section('title', trans('Clients Page'))
@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="">{{trans('clients')}}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                            <li class="breadcrumb-item active "> {{trans('clients table')}} </li>
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
                        <h3 class="card-title">{{__('clients table')}}</h3>
                        <div class="card-tools">

                            @if(!Auth::user()->hasRole('agent'))

                                <a class="btn btn-secondary btn-sm"  href="{{ route('clients.trashed') }}">
                                    <i class="fas fa-trash"></i> @lang('Trashed clients')
                                </a>

                            @endif

                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createclientModal" aria-controls="createclientModal" title="@lang('Add Client')">
                                <i class="fas fa-user-plus"></i> @lang('add client')
                            </button>

                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="table-responsive">
                        <table id="client-table" class="table table-bordered table-hover w-100">
                            <thead>
                            <tr>
                                <th class="text-center"> {{ trans('id') }}</th>
                                <th class="text-center"><i class="fas fa-user"></i> {{ trans('name') }}</th>
{{--                                <th class="text-center"><i class="fas fa-store"></i> {{ trans('store') }}</th>--}}
                                <th class="text-center"><i class="fas fa-store"></i> {{ trans('balance') }}</th>
                                <th class="text-center"><i class="fas fa-phone"></i> {{ trans('phone') }}</th>
                                <th class="text-center"><i class="fas fa-map-marker-alt"></i> {{ trans('address') }}</th>
                                <th class="text-center">
                                {{--                                    <i class="fas fa-money-bill-wave"></i> --}}
                                {{ trans('balance') }}</th>
{{--                                <th><pre class="p-0 m-0">        <i class="fas fa-cogs"> </i>{{ trans('actions') }}        </pre></th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            <!-- سيتم تحميل البيانات ديناميكيًا هنا -->
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- /.card -->
            </div>

            @include('layouts.parts.modalsForclients')

        @endsection

        @push('cssModal')
            <link rel="stylesheet" href="{{asset('dist/css/myCustomTable.css')}}">
        @endpush
@push('jsModal')

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>



                $(document).ready(function () {
                    const userIsNotAgent = {{ Auth::user()->hasRole('agent') ? 'false' : 'true' }};
                    {{--const userRole = "{{ $userRole }}";--}}
                    // إعداد الجدول باستخدام DataTables
                    let table = $('#client-table').DataTable({
                        processing: true,
                        pageLength: 100,
                        serverSide: true,
                        searching:true,
                        ajax: "{{ route('clients.index') }}",
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }, // العمود التسلسلي
                            { data: 'name', name: 'name' , searchable: true},
                            { data: 'balance', name: 'balance' },
                            { data: 'store', name: 'store' , visible:userIsNotAgent},
                            { data: 'phone', name: 'phone' , visible:userIsNotAgent },
                            { data: 'address', name: 'address', searchable: true , visible:userIsNotAgent},
                            // { data: 'actions', name: 'actions', orderable: false, searchable: false }
                        ],
                        columnDefs: [
                            {
                                // Target the 'store' column index (2 in this case)
                                targets: [5],
                                visible: userIsNotAgent, // Show or hide based on role
                            }
                        ],
                        dom: '<"row d-flex align-items-center p-3"<"col-md-3 col-12"l><"col-md-6 col-12 text-md-end text-center"B><"col-md-3 col-12"f>>' +
                            '<"row"<"col-md-12"t>>' +
                            '<"row"<"col-md-6"i><"col-md-6"p>>',
                        buttons: [
                            {{--{--}}
                            {{--    extend: 'pdfHtml5',--}}
                            {{--    text: '{{ trans("export_to_pdf") }}',--}}
                            {{--    className: 'btn btn-danger btn-sm',--}}
                            {{--    orientation: 'portrait',--}}
                            {{--    pageSize: 'A4',--}}
                            {{--    exportOptions: {--}}
                            {{--        columns: [0, 1, 2, 3, 4, 5]--}}
                            {{--    },--}}
                            {{--    customize: function (doc) {--}}
                            {{--        doc.content.splice(0, 0, {--}}
                            {{--            text: 'Client Report',--}}
                            {{--            style: 'header',--}}
                            {{--            alignment: 'center',--}}
                            {{--            fontSize: 18,--}}
                            {{--            margin: [0, 0, 0, 20]--}}
                            {{--        });--}}
                            {{--    }--}}
                            {{--},--}}
                            {
                                extend: 'excelHtml5',
                                text: '{{ trans("export_to_excel") }}',
                                className: 'btn btn-success btn-sm',
                                exportOptions: {
                                    columns: [1, 2]
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
                    //view-client modal
                    document.addEventListener('click', function (e) {
                        if (e.target.closest('.view-client')) {
                            var id = e.target.getAttribute('data-id')
                            var name = e.target.getAttribute('data-name')
                            var company_name = e.target.getAttribute('data-company_name')
                            // var phone = e.target.getAttribute('data-phone')
                            var address = e.target.getAttribute('data-address')
                            // var website = e.target.getAttribute('data-website')
                            var balance = e.target.getAttribute('data-balance')

                            document.getElementById('modal-client-name').innerText = name
                            document.getElementById('modal-client-companyName').innerText = company_name
                            // document.getElementById('modal-phone').innerText = phone
                            // document.getElementById('modal-client-website').innerText = website
                            document.getElementById('title').innerText = address
                            document.getElementById('modal-client-balance').innerText = address
                            document.getElementById('modal-client-balance').innerText = balance
                            document.getElementById('modal-address').innerText = address

                            var modal = document.getElementById('clientModal');
                            var bootstrapModal = new bootstrap.Modal(modal);

                            bootstrapModal.show();
                        }
                    });
                    // Create client Form Submission
                    const storeclientRoute = "{{ route('clients.store') }}";

                    document.getElementById('createclientForm').addEventListener('submit', function (event) {
                        event.preventDefault();
                        const form = this;
                        const submitButton = form.querySelector('.submit-creating-form');
                        const formData = new FormData(form);

                        const data = {};
                        formData.forEach((value, key) => {
                            data[key] = value;
                        });

                        disableButton(submitButton, true);

                        fetch(storeclientRoute, {
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
                                    $('#createclientModal').modal('hide').removeClass('show').addClass('fade');
                                    $('body').removeClass('modal-open');
                                    $('body').css('padding-right', '');
                                    $('.modal-backdrop').remove();

                                    Swal.fire('Success', 'Client created successfully.', 'success');
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



                    //edit-client modal
                    document.addEventListener('click' , function (e) {
                        if(e.target.closest('.edit-client')){
                            var id = e.target.getAttribute('data-id')
                            var name = e.target.getAttribute('data-name')
                            var company_name = e.target.getAttribute('data-company_name')
                            var phone = e.target.getAttribute('data-phone')
                            var address = e.target.getAttribute('data-address')
                            var website = e.target.getAttribute('data-website')

                            document.getElementById('edit-id').value = id
                            document.getElementById('edit-name').value = name
                            document.getElementById('edit-address').value = address
                            document.getElementById('edit-phone').value = phone
                            document.getElementById('edit-company-name').value = company_name
                            document.getElementById('edit-website').value = website
                            // document.getElementById('edit-phone').value = phone


                            // document.getElementById('editclientForm').setAttribute('action' , '/clients/' + clientId )
                            var modal = document.getElementById('editclientModal');
                            var bootstrapModal = new bootstrap.Modal(modal);
                            bootstrapModal.show();
                        }
                    })

                    document.getElementById('editclientForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        var form = this;
                        const formData = new FormData(form);

                        // تحويل FormData إلى JSON
                        const data = {};
                        formData.forEach((value, key) => {
                            data[key] = value;
                        });

                        // استبدال :id في الرابط
                        const updateUserRoute = "{{ route('clients.update', ':id') }}";
                        const finalRoute = updateUserRoute.replace(':id', data.id);

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
                                    $('#editclientModal').modal('hide').removeClass('show').addClass('fade');

                                    $('body').removeClass('modal-open'); // إزالة خاصية منع التمرير
                                    $('body').css('padding-right', ''); // إعادة ضبط الحشو إذا كان مضافًا
                                    $('.modal-backdrop').remove(); // إزالة الخلفية الداكنة
                                    Swal.fire('Success', 'Client updated successfully.', 'success');
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

                    // Confirmation before deleting a client
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
                    $('#createclientModal, #editclientModal').on('hidden.bs.modal', function () {
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
