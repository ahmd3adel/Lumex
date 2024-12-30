@extends('layouts.index')
@section('title' , 'USERS PAGE')
@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class=""> Users</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active "> Users page </li>
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
                        <h3 class="card-title">Users Table</h3>
                        <div class="card-tools">
                            <a class="btn btn-danger btn-sm"  href="{{ route('users.trashed') }}">
                                <i class="fas fa-trash"></i> Trashed Users
                            </a>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createUserModal">
                                <i class="fas fa-user-plus"></i> Add User
                            </button>



                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="table-responsive ">
                        <table id="user-table" class="table table-bordered table-hover w-100">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Phone</th>
                                <th>Roles</th>
                                <th>Status</th>
                                <th>Actions</th>
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

            <style>
                /*.modal-header.bg-primary {*/
                /*    border-bottom: 2px solid #004085;*/
                /*}*/
                /*.modal-footer {*/
                /*    border-top: 1px solid #e9ecef;*/
                /*}*/

                /*.table th,*/
                /*.table td {*/
                /*    vertical-align: middle; !* توسيط النص عموديًا *!*/
                /*    text-align: center; !* توسيط النص أفقيًا *!*/
                /*    white-space: nowrap; !* منع تكسير النصوص *!*/
                /*}*/
                .table th {
                    background-color: #f8f9fa; /* لون خلفية الأعمدة */
                    font-weight: bold; /* نص عريض */
                }
                .table tbody tr:hover {
                    background-color: #f1f1f1; /* لون خلفية الصف عند التمرير */
                }

                @media (min-width: 768px) {
                    .text-md-end {
                        text-align: right !important;
                    }
                }

                .action-buttons {
                    display: flex;
                    flex-wrap: nowrap; /* Prevent buttons from wrapping */
                    gap: 0.5rem; /* Add space between buttons */
                    justify-content: flex-start;
                }

                .action-buttons .btn {
                    white-space: nowrap; /* Prevent text from wrapping */
                }

                /* For small screens */
                @media (max-width: 768px) {
                    .action-buttons .btn {
                        font-size: 0.8rem; /* Reduce font size */
                        padding: 0.25rem 0.5rem; /* Adjust padding for buttons */
                    }
                    .action-buttons {
                        gap: 0.25rem; /* Reduce gap on small screens */
                    }
                }
                /* Action Buttons Container */
                .action-buttons {
                    display: flex;
                    flex-wrap: nowrap; /* Prevent wrapping */
                    gap: 0.5rem; /* Add space between buttons */
                    align-items: center; /* Align buttons vertically */
                }

                /* Buttons Styling */
                .action-buttons .btn {
                    white-space: nowrap; /* Prevent text from wrapping */
                }

                /* Adjustments for Small Screens */
                @media (max-width: 768px) {
                    .action-buttons .btn {
                        font-size: 0.75rem; /* Reduce font size for buttons */
                        padding: 0.25rem 0.5rem; /* Adjust padding for better fit */
                    }
                    .action-buttons {
                        gap: 0.25rem; /* Reduce gap between buttons */
                    }
                }
                /* Ensure the action buttons fit inline */
                .action-buttons {
                    display: flex;
                    flex-wrap: nowrap; /* Prevent wrapping */
                    gap: 0.5rem; /* Add space between buttons */
                    justify-content: flex-start;
                }

                /* Adjust button styling */
                .action-buttons .btn {
                    white-space: nowrap; /* Prevent text wrapping inside buttons */
                }

                /* Adjust column width for table layout */
                .dataTables_wrapper .dataTables_scrollHeadInner table {
                    table-layout: auto !important; /* Allow columns to adjust dynamically */
                }

                /* Responsive adjustments for smaller screens */
                @media (max-width: 768px) {
                    .action-buttons .btn {
                        font-size: 0.8rem; /* Reduce font size */
                        padding: 0.25rem 0.5rem; /* Adjust padding for buttons */
                    }
                    .action-buttons {
                        gap: 0.25rem; /* Reduce gap on small screens */
                    }
                }


            </style>

        @endpush
@push('jsModal')

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>

                $(document).ready(function () {


                    let table = $('#user-table').DataTable({
                        processing: true, // Show loading indicator
                        serverSide: true, // Enable server-side processing
                        ajax: "{{ route('users.index') }}", // Dynamic data route
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
                                text: 'Export to PDF',
                                className: 'btn btn-danger btn-sm',
                                orientation: 'portrait',
                                pageSize: 'A4',
                                exportOptions: {
                                    columns: [0, 1, 2, 3] // Exported columns
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
                        let button = $(this);
                        let userId = button.data('id');

                        $.ajax({
                            url: "{{ route('users.toggleStatus') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: userId
                            },
                            success: function (response) {
                                if (response.success) {
                                    button
                                        .toggleClass('btn-success btn-danger')
                                        .text(response.new_status === 'active' ? 'Active' : 'Inactive');
                                } else {
                                    Swal.fire('Error', 'Failed to update status.', 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                            }
                        });
                    });



                    {{--document.getElementById('editUserForm').addEventListener('submit', function (e) {--}}
                    {{--    e.preventDefault();--}}
                    {{--    var form = this;--}}
                    {{--    const submitButton = form.querySelector('.submit-editing-form');--}}
                    {{--    const formData = new FormData(form);--}}

                    {{--    for (let [key, value] of formData.entries()) {--}}
                    {{--        console.log(`${key}: ${value}`); // تحقق من القيم المرسلة--}}
                    {{--    }--}}

                    {{--    var usersId = formData.get('id');--}}
                    {{--    const updateUserRoute = "{{ route('users.update', ':id') }}";--}}
                    {{--    const finalRoute = updateUserRoute.replace(':id', usersId);--}}

                    {{--    disableButton(submitButton, true);--}}

                    {{--    form.querySelectorAll('.is-invalid').forEach(input => {--}}
                    {{--        input.classList.remove('is-invalid');--}}
                    {{--        const errorFeedback = input.nextElementSibling;--}}
                    {{--        if (errorFeedback && errorFeedback.classList.contains('invalid-feedback')) {--}}
                    {{--            errorFeedback.remove();--}}
                    {{--        }--}}
                    {{--    });--}}

                    {{--    fetch(finalRoute, {--}}
                    {{--        method: 'PUT',--}}
                    {{--        body: formData,--}}
                    {{--        headers: {--}}
                    {{--            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),--}}
                    {{--            'Accept': 'application/json'--}}
                    {{--        },--}}
                    {{--    })--}}
                    {{--        .then(response => {--}}
                    {{--            console.log('Response Status:', response.status); // تحقق من حالة الاستجابة--}}
                    {{--            if (!response.ok) {--}}
                    {{--                // إذا كانت الاستجابة غير ناجحة--}}
                    {{--                throw response;--}}
                    {{--            }--}}
                    {{--            return response.json();--}}
                    {{--        })--}}
                    {{--        .then(data => {--}}
                    {{--            console.log('Server Response:', data);--}}
                    {{--            if (data.success) {--}}
                    {{--                disableButton(submitButton, true);--}}
                    {{--                $('#editUserModal').modal('hide');--}}
                    {{--                Swal.fire('Success', 'User updated successfully.', 'success');--}}
                    {{--                table.ajax.reload(); // Reload DataTable--}}
                    {{--            } else {--}}
                    {{--                handleValidationErrors(form, data.errors);--}}
                    {{--            }--}}
                    {{--        })--}}
                    {{--        .catch(async error => {--}}
                    {{--            if (error.json) {--}}
                    {{--                const errorData = await error.json();--}}
                    {{--                console.error('Validation Errors:', errorData.errors);--}}
                    {{--                Swal.fire('Validation Error', JSON.stringify(errorData.errors), 'error');--}}
                    {{--            } else {--}}
                    {{--                console.error('Error:', error);--}}
                    {{--                Swal.fire('Error', 'An unexpected error occurred.', 'error');--}}
                    {{--            }--}}
                    {{--        })--}}
                    {{--        .finally(() => disableButton(submitButton, false));--}}
                    {{--});--}}


                    document.getElementById('editUserForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        var form = this;
                        const formData = new FormData(form);

                        // عرض البيانات المرسلة في الكونسول
                        for (let [key, value] of formData.entries()) {
                            console.log(`${key}: ${value}`);
                        }

                        const updateUserRoute = "{{ route('users.update', ':id') }}";
                        const finalRoute = updateUserRoute.replace(':id', formData.get('id'));

                        console.log('Sending to:', finalRoute);

                        fetch(finalRoute, {
                            method: 'PUT',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        })
                            .then((response) => {
                                console.log('Response Status:', response.status);
                                return response.json();
                            })
                            .then((data) => {
                                console.log('Server Response:', data);
                            })
                            .catch((error) => {
                                console.error('Fetch Error:', error);
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
