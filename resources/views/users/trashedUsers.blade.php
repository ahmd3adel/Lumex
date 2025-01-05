@extends('layouts.index')
@section('title' , 'TRASHED PAGE')
@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="">{{trans('users')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                            <li class="breadcrumb-item active "> {{trans('users table')}} </li>
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
                        <h3 class="card-title">{{__('users table')}}</h3>
                        <div class="card-tools">
                            <a class="btn btn-secondary btn-sm"  href="{{ route('users.trashed') }}">
                                <i class="fas fa-trash"></i> @lang('Trashed Users')
                            </a>
                            <a class="btn btn-primary btn-sm" href="{{route('users.index')}}">
                                <i class="fas fa-user"></i> @lang('users')
                            </a>



                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="table-responsive ">
                        <table id="user-table" class="table table-bordered table-hover w-100">
                            <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> {{ trans('id') }}</th>
                                <th><i class="fas fa-user"></i> {{ trans('name') }}</th>
                                <th><i class="fas fa-envelope"></i> {{ trans('email') }}</th>
                                <th><i class="fas fa-at"></i> {{ trans('username') }}</th>
                                <th><i class="fas fa-phone"></i> {{ trans('phone') }}</th>
                                <th><i class="fas fa-user-tag"></i> {{ trans('roles') }}</th>
                                <th><i class="fas fa-toggle-on"></i> {{ trans('status') }}</th>
                                <th><i class="fas fa-cogs"></i> {{ trans('actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>



                </div>
                <!-- /.card -->
            </div>

            @include('layouts.parts.modals')

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
                    let table = $('#user-table').DataTable({
                        processing: true, // Show loading indicator
                        serverSide: true, // Enable server-side processing
                        ajax: "{{ route('users.trashed') }}", // Dynamic data route
                        columns: [
                            { data: 'id', name: 'id' },
                            { data: 'name', name: 'name' },
                            { data: 'email', name: 'email' },
                            { data: 'username', name: 'username' },
                            { data: 'phone', name: 'phone' },
                            { data: 'roles', name: 'roles', orderable: false, searchable: false },
                            { data: 'status', name: 'status' },
                            { data: 'action', name: 'action', orderable: false, searchable: false }
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
                                        text: 'User Report',
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
                        }
                    });

                    //view-user modal
                    document.addEventListener('click', function (e) {
                        if (e.target.closest('.view-user')) {
                            var id = e.target.getAttribute('data-id')
                            var name = e.target.getAttribute('data-name')
                            var username = e.target.getAttribute('data-username')
                            var phone = e.target.getAttribute('data-phone')
                            var role = e.target.getAttribute('data-role')
                            var email = e.target.getAttribute('data-email')
                            var  joined= e.target.getAttribute('data-joined')

                            document.getElementById('modal-user-name').innerText = name
                            document.getElementById('modal-phone').innerText = phone
                            document.getElementById('modal-user-email').innerText = email
                            document.getElementById('modal-user-role').innerText = role
                            document.getElementById('modal-user-joined').innerText = joined
                            document.getElementById('modal-username').innerText = username

                            var modal = document.getElementById('userModal');
                            var bootstrapModal = new bootstrap.Modal(modal);

                            bootstrapModal.show();
                        }
                    });


                    $(document).on('click', '.toggle-status', function () {
                        let userId = $(this).data('id');
                        $.ajax({
                            url: "{{ route('users.toggleStatus') }}",
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

                    //edit-user modal
                    document.addEventListener('click' , function (e) {
                        if(e.target.closest('.edit-user')){
                            var userId = e.target.getAttribute('data-id');
                            var name = e.target.getAttribute('data-name')
                            var username = e.target.getAttribute('data-username')
                            var phone = e.target.getAttribute('data-phone')
                            var role = e.target.getAttribute('data-role')
                            var email = e.target.getAttribute('data-email')


                            document.getElementById('editId').value = userId
                            document.getElementById('edit-name').value = name
                            document.getElementById('edit-phone').value = phone
                            document.getElementById('edit-email').value = email
                            document.getElementById('edit-role').value = role
                            document.getElementById('edit-username').value = username
                            document.getElementById('editUserForm').setAttribute('action' , '/users/' + userId )
                            var modal = document.getElementById('editUserModal');
                            var bootstrapModal = new bootstrap.Modal(modal);
                            bootstrapModal.show();
                        }
                    })

                    document.getElementById('editUserForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        var form = this;
                        const formData = new FormData(form);

                        // تحويل FormData إلى JSON
                        const data = {};
                        formData.forEach((value, key) => {
                            data[key] = value;
                        });

                        // استبدال :id في الرابط
                        const updateUserRoute = "{{ route('users.update', ':id') }}";
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
                                        throw errorData; // إرسال الأخطاء إلى الكتلة catch
                                    });
                                }
                                return response.json(); // تحليل JSON إذا كانت الاستجابة ناجحة
                            })
                            .then((data) => {
                                if (data.success) {
                                    // تنفيذ عند النجاح
                                    $('#editUserModal').modal('hide').removeClass('show').addClass('fade');

                                    $('body').removeClass('modal-open'); // إزالة خاصية منع التمرير
                                    $('body').css('padding-right', ''); // إعادة ضبط الحشو إذا كان مضافًا
                                    $('.modal-backdrop').remove(); // إزالة الخلفية الداكنة
                                    Swal.fire('Success', 'User created successfully.', 'success');
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

                    // Confirmation before deleting a user
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
                    $('#createUserModal, #editUserModal').on('hidden.bs.modal', function () {
                        const form = this.querySelector('form');
                        if (form) resetForm(form);
                    });

                    // Create User Form Submission
                    const storeUserRoute = "{{ route('users.store') }}";
                    document.getElementById('createUserForm').addEventListener('submit', function (event) {
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

                        fetch(storeUserRoute, {
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
                                    $('#createUserModal').modal('hide');
                                    Swal.fire('Success', 'User created successfully.', 'success');
                                    table.ajax.reload(); // Reload DataTable
                                } else {
                                    handleValidationErrors(form, data.errors);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'An unexpected error occurred.', 'error');
                            })
                            .finally(() => disableButton(submitButton, false));
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
