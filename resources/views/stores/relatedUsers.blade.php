@extends('layouts.index')
@section('title', $pageTitle . ' PAGE')

@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="">{{trans('store')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                            <li class="breadcrumb-item active "> {{trans('store table')}}  </li>
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
                        <h3 class="card-title">{{__('store table')}} for {{$store->name}}</h3>
                        <div class="card-tools">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createstoreModal">
                                <i class="fas fa-store-plus"></i> @lang('add store')
                            </button>



                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="table-responsive ">
                        <table id="store-table" class="table table-bordered table-hover w-100">
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

        @include('layouts.parts.modalsForStores')

        @endsection

        @push('cssModal')
            <link rel="stylesheet" href="{{asset('dist/css/myCustomTable.css')}}">
        @endpush
@push('jsModal')

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            var storeId = "{{$id}}";
            var ajaxUrl = "{{ route('stores.users', ':id') }}";
            var ajax1 = ajaxUrl.replace(':id' , storeId)
            $('#createstoreModal, #editstoreModal').on('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) resetForm(form);
            });

            // Initialize DataTable
            let table = $('#store-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: ajax1,
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'username', name: 'username' },
                    { data: 'phone', name: 'phone' },
                    { data: 'roles', name: 'roles' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                dom: '<"row d-flex align-items-center p-3"<"col-md-3 col-12"l><"col-md-6 col-12 text-md-end text-center"B><"col-md-3 col-12"f>>' +
                    '<"row"<"col-md-12"t>>' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        text: '{{trans("Export To PDF")}}',
                        className: 'btn btn-danger btn-sm',
                        orientation: 'portrait',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                        customize: function (doc) {
                            doc.content.splice(0, 0, {
                                text: 'Store Report',
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
                        exportOptions: { columns: [0, 1, 2, 3] }
                    }
                ],
                lengthMenu: [10, 25, 50, 100],
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

            // View Store Modal
            document.addEventListener('click', function (e) {
                if (e.target.closest('.view-store')) {
                    const id = e.target.getAttribute('data-id');
                    const name = e.target.getAttribute('data-name');
                    const location = e.target.getAttribute('data-location');
                    const created = e.target.getAttribute('data-created');
                    const updated = e.target.getAttribute('data-updated');

                    document.getElementById('modal-store-name').innerText = name;
                    document.getElementById('modal-store-location').innerText = location;
                    document.getElementById('modal-store-created-at').innerText = created;
                    document.getElementById('modal-store-updated-at').innerText = updated;

                    const modal = document.getElementById('storeModal');
                    const bootstrapModal = new bootstrap.Modal(modal);

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
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
            @endif
            // Edit Store Modal
            document.addEventListener('click', function (e) {
                if (e.target.closest('.edit-store')) {
                    const storeId = e.target.getAttribute('data-id');
                    const name = e.target.getAttribute('data-name');
                    const location = e.target.getAttribute('data-location');

                    document.getElementById('editId').value = storeId;
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-location').value = location;

                    const modal = document.getElementById('editstoreModal');
                    const bootstrapModal = new bootstrap.Modal(modal);
                    bootstrapModal.show();
                }
            });

            // Edit Store Form Submission
            document.getElementById('editstoreForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);

                // Convert FormData to JSON object
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                // Update route with store ID
                const updateStoreRoute = "{{ route('stores.update', ':id') }}";
                const finalRoute = updateStoreRoute.replace(':id', data.id);

                // Disable submit button and add spinner
                const submitButton = form.querySelector('.submit-editing-form');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                fetch(finalRoute, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(data),
                })
                    .then((response) => {
                        if (!response.ok) {
                            return response.json().then((errorData) => {
                                console.error('Validation errors:', errorData); // Log validation errors
                                throw errorData;
                            });
                        }
                        return response.json();
                    })
                    .then((data) => {
                        if (data.success) {
                            // Close modal
                            $('#editstoreModal').modal('hide').removeClass('show').addClass('fade');
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                            $('.modal-backdrop').remove();

                            // Success message
                            Swal.fire('Success', 'Store updated successfully.', 'success');

                            // Reset form
                            form.reset();

                            // Reload DataTable
                            table.ajax.reload();
                        } else {
                            console.error('Server validation errors:', data.errors); // Log server errors
                            handleValidationErrors(form, data.errors);
                        }
                    })
                    .catch((error) => {
                        if (error.errors) {
                            // Handle validation errors
                            handleValidationErrors(form, error.errors);
                        } else {
                            // Log unexpected errors
                            console.error('Unexpected error:', error);
                            Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
                        }
                    })
                    .finally(() => {
                        // Re-enable submit button and restore text
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                    });
            });

            // Delete Store Confirmation
            $(document).on('submit', 'form.delete', function (e) {
                e.preventDefault();
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
                        this.submit();
                    }
                });
            });

            // Create Store Form Submission
            const storeStoreRoute = "{{ route('stores.store') }}";
            document.getElementById('createstoreForm').addEventListener('submit', function (event) {
                event.preventDefault();
                const form = this;
                const submitButton = form.querySelector('.submit-creating-form');
                const formData = new FormData(form);

                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                disableButton(submitButton, true);

                fetch(storeStoreRoute, {
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
                            $('#createstoreModal').modal('hide').removeClass('show').addClass('fade');
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                            $('.modal-backdrop').remove();

                            Swal.fire('Success', 'Store created successfully.', 'success');
                            form.reset();
                            table.ajax.reload();
                        } else {
                            handleValidationErrors(form, data.errors);
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'An unexpected error occurred.', 'error');
                    })
                    .finally(() => disableButton(submitButton, false));
            });

            // Utility Functions
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
        });

    </script>
    @endpush
