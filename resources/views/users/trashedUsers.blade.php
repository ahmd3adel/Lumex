@extends('layouts.index')
@section('title' , 'Trashed USERS PAGE')
@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Trashed Users</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active ">Trashed Users page </li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
@endsection

        @section('content')
            <div class="container-fluid">
                <!-- Card for the table -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createUserModal">
                                <i class="fas fa-user-plus"></i> Add User
                            </button>



                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="user-table" class="table table-bordered table-hover w-100">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>phone</th>
                                    <th>username</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- /.card -->
            </div>

            <!-- start show user Modal -->
            <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="userModalLabel">
                                <i class="fas fa-user-circle"></i> User Details
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div class="card card-primary card-outline shadow-none">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-user"></i> Name:</strong> <span id="modal-user-name" class="text-primary"></span></p>
                                            <p><strong><i class="fas fa-envelope"></i> Email:</strong> <span id="modal-user-email" class="text-primary"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-briefcase"></i> Role:</strong> <span id="modal-user-role" class="text-primary"></span></p>
                                            <p><strong><i class="fas fa-calendar-alt"></i> Joined:</strong> <span id="modal-user-joined" class="text-primary"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-briefcase"></i> username:</strong> <span id="modal-username" class="text-primary"></span></p>
                                        </div>

                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-calendar-alt"></i> phone:</strong> <span id="modal-phone" class="text-primary"></span></p>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- start create user Modal -->
            <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="createUserModalLabel"><i class="fas fa-user-plus"></i> Create New User</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <form id="createUserForm" action="{{ route('users.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <!-- Name Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <!-- Email Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="Username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="Username" name="username" placeholder="Enter username">
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Role Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="role" class="form-label">Role</label>
{{--                                        <select class="form-control" id="role" name="role">--}}
{{--                                            <option value="">Select role</option>--}}
{{--                                            @foreach($roles as $role)--}}
{{--                                                <option value="{{$role->name}}">{{ucfirst($role->name)}}</option>--}}

{{--                                            @endforeach--}}

{{--                                        </select>--}}
                                    </div>
                                    <!-- Password Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" autocomplete="off" class="form-control" id="password" name="password" placeholder="Enter password">
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"  data-dismiss="modal"><i class="fas fa-times"></i> Close</button>

                                <button type="submit" class="btn btn-primary submit-creating-form" onclick="disableButton(this)">
                                    <i class="fas fa-save"></i> Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end create user Modal -->

        @endsection

        <style>
            .modal-header.bg-primary {
                border-bottom: 2px solid #004085;
            }
            .modal-footer {
                border-top: 1px solid #e9ecef;
            }
            .table th,
            .table td {
                vertical-align: middle;
                text-align: center;
                white-space: nowrap;
            }
            .table th {
                background-color: #f8f9fa;
                font-weight: bold;
            }
            .table tbody tr:hover {
                background-color: #f1f1f1;
            }
            @media (min-width: 768px) {
                .text-md-end {
                    text-align: right !important;
                }
            }
        </style>
@push('jsModal')

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>

                $(document).ready(function () {
                    // Initialize DataTable
                    if ($.fn.DataTable.isDataTable('#user-table')) {
                        $('#user-table').DataTable().destroy();
                    }
                    let table = $('#user-table').DataTable({
                        processing: true, // Show loading indicator
                        serverSide: true, // Enable server-side processing
                        ajax: "{{ route('users.trashed') }}", // Dynamic data route
                        columns: [
                            { data: 'id', name: 'id' },
                            { data: 'name', name: 'name' },
                            { data: 'email', name: 'email' },
                            { data: 'roles', name: 'roles', orderable: false, searchable: false },
                            { data: 'phone', name: 'phone' },
                            { data: 'username', name: 'username' },
                            { data: 'action', name: 'action', orderable: false, searchable: false }
                        ],
                        dom: '<"row d-flex align-items-center"<"col-md-3 col-12"l><"col-md-6 col-12 text-md-end text-center"B><"col-md-3 col-12"f>>' +
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

                    // Handle View User Modal
                    $(document).on('click', '.view-user', function () {
                        let name = $(this).data('name');

                        let phone = $(this).data('phone');
                        let email = $(this).data('email');
                        let role = $(this).data('role');
                        let joined = $(this).data('joined');
                        let username = $(this).data('username');

                        $('#modal-user-name').text(name);
                        $('#modal-phone').text(phone); // تحديث الحقل phone
                        $('#modal-user-email').text(email);
                        $('#modal-user-role').text(role);
                        $('#modal-user-joined').text(joined);
                        $('#modal-username').text(username);

                        $('#userModal').modal('show');
                    });



                    // Handle Toggle Status Button
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
                                    alert('Error updating status.');
                                }
                            },
                            error: function () {
                                alert('An error occurred. Please try again.');
                            }
                        });
                    });
                });

            </script>

            <script>
                $(document).ready(function () {
                    // SweetAlert for success messages
                    @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    @endif

                    // Confirmation before deleting a user
                    $(document).on('click', '.restore-user', function (e) {
                        e.preventDefault();
                        let form = $(this).closest('form');

                        Swal.fire({
                            title: 'Are you sure to restore the user?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, restore it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit(); // Submit the form if confirmed
                            }
                        });
                    });
                    $(document).on('click', '.foce-delete-user', function (e) {
                        e.preventDefault();
                        let form = $(this).closest('form');

                        Swal.fire({
                            title: 'Are you sure to force delete the user?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit(); // Submit the form if confirmed
                            }
                        });
                    });
                });

                function disableButton(button) {
                    button.disabled = true; // تعطيل الزر
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...'; // تغيير النص أثناء المعالجة

                    // إرسال النموذج يدويًا
                    button.form.submit();
                }


            </script>


    @endpush
