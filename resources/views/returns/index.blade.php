@extends('layouts.index')
@section('title', $pageTitle . ' PAGE')
@push('style')
    <style>
        .custom-modal-width {
            width: 100%; /* العرض الافتراضي */
            max-width: 1000px; /* العرض الأقصى */
            margin: auto;
        }


    </style>
@endpush
@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="">{{trans('invoice')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                            <li class="breadcrumb-item active "> {{trans('returns table')}} </li>
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
                        <h3 class="card-title text-capitalize">{{__('Returns Table')}}</h3>
                        <div class="card-tools">
                            <a class="btn btn-primary" href="{{route('returns.create')}}">
                                <i class="fas fa-file-invoice-plus"></i> @lang('add return')
                            </a>
                        </div>

                    </div>
                    <!-- /.card-header -->

                    <div class="table-responsive">
                        <table id="return-table" class="table table-bordered table-hover w-100 text-center">
                            <thead>
                            <tr>
                                <th class="text-center"> {{ trans('id') }}</th>
                                <th> {{ trans('return_no') }}</th>
                                <th> {{ trans('store') }}</th>
                                <th> {{ trans('client') }}</th>
                                <th> {{ trans('return_date') }}</th>
                                <th> {{ trans('total') }}</th>
                                <th><i class="fas fa-cog"></i> {{ trans('actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>




                </div>
                <!-- /.card -->
            </div>

        @include('layouts.parts.modalsForinvoices')

        @endsection

        @push('cssModal')
            <link rel="stylesheet" href="{{asset('dist/css/myCustomTable.css')}}">
        @endpush
@push('jsModal')

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('#createinvoiceModal, #editinvoiceModal').on('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) resetForm(form);
            });

            const userIsNotAgent = {{ Auth::user()->hasRole('agent') ? 'false' : 'true' }};

            // Initialize DataTable

            let table = $('#return-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('returns.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'return_no', name: 'return_no' },
                    { data: 'store', name: 'store' }, // Store column
                    { data: 'client', name: 'client' }, // Store column
                    { data: 'return_date', name: 'return_date' },
                    { data: 'total', name: 'total' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
                columnDefs: [
                    {
                        // Target the 'store' column index (2 in this case)
                        targets: [2],
                        visible: userIsNotAgent, // Show or hide based on role
                    }
                ],
                dom: '<"row d-flex align-items-center p-3"<"col-md-3 col-12"l><"col-md-6 col-12 text-md-end text-center"B><"col-md-3 col-12"f>>' +
                    '<"row"<"col-md-12"t>>' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        className: 'btn btn-danger btn-sm',
                        orientation: 'portrait',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5] // Include specific columns
                        },
                        customize: function (doc) {
                            doc.content.splice(0, 0, {
                                text: 'Invoice Report',
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
                            columns: [0, 1, 2, 3, 4, 5] // Include specific columns
                        }
                    }
                ],
                lengthMenu: [10, 25, 50, 100],
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    search: "_INPUT_",
                    searchPlaceholder: "Search invoices...",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                responsive: true
            });

            // View invoice Modal
            {{--document.addEventListener('click', function (e) {--}}
            {{--    if (e.target.closest('.view-invoice')) {--}}
            {{--        const id = e.target.getAttribute('data-id');--}}
            {{--        const name = e.target.getAttribute('data-name');--}}
            {{--        const location = e.target.getAttribute('data-location');--}}
            {{--        const created = e.target.getAttribute('data-created');--}}
            {{--        const updated = e.target.getAttribute('data-updated');--}}

            {{--        document.getElementById('modal-invoice-name').innerText = name;--}}
            {{--        document.getElementById('modal-invoice-location').innerText = location;--}}
            {{--        document.getElementById('modal-invoice-created-at').innerText = created;--}}
            {{--        document.getElementById('modal-invoice-updated-at').innerText = updated;--}}

            {{--        const modal = document.getElementById('invoiceModal');--}}
            {{--        const bootstrapModal = new bootstrap.Modal(modal);--}}

            {{--        bootstrapModal.show();--}}
            {{--    }--}}
            {{--});--}}

            {{--// Edit invoice Modal--}}
            {{--document.addEventListener('click', function (e) {--}}
            {{--    if (e.target.closest('.edit-invoice')) {--}}
            {{--        const invoiceId = e.target.getAttribute('data-id');--}}
            {{--        const name = e.target.getAttribute('data-name');--}}
            {{--        const location = e.target.getAttribute('data-location');--}}

            {{--        document.getElementById('editId').value = invoiceId;--}}
            {{--        document.getElementById('edit-name').value = name;--}}
            {{--        document.getElementById('edit-location').value = location;--}}

            {{--        const modal = document.getElementById('editinvoiceModal');--}}
            {{--        const bootstrapModal = new bootstrap.Modal(modal);--}}
            {{--        bootstrapModal.show();--}}
            {{--    }--}}
            {{--});--}}

            {{--// Edit invoice Form Submission--}}
            {{--document.getElementById('editinvoiceForm').addEventListener('submit', function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    const form = this;--}}
            {{--    const formData = new FormData(form);--}}

            {{--    // Convert FormData to JSON object--}}
            {{--    const data = {};--}}
            {{--    formData.forEach((value, key) => {--}}
            {{--        data[key] = value;--}}
            {{--    });--}}

            {{--    // Update route with invoice ID--}}
            {{--    const updateinvoiceRoute = "{{ route('invoices.update', ':id') }}";--}}
            {{--    const finalRoute = updateinvoiceRoute.replace(':id', data.id);--}}

            {{--    // Disable submit button and add spinner--}}
            {{--    const submitButton = form.querySelector('.submit-editing-form');--}}
            {{--    submitButton.disabled = true;--}}
            {{--    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';--}}

            {{--    fetch(finalRoute, {--}}
            {{--        method: 'PUT',--}}
            {{--        headers: {--}}
            {{--            'Content-Type': 'application/json',--}}
            {{--            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),--}}
            {{--        },--}}
            {{--        body: JSON.stringify(data),--}}
            {{--    })--}}
            {{--        .then((response) => {--}}
            {{--            if (!response.ok) {--}}
            {{--                return response.json().then((errorData) => {--}}
            {{--                    console.error('Validation errors:', errorData); // Log validation errors--}}
            {{--                    throw errorData;--}}
            {{--                });--}}
            {{--            }--}}
            {{--            return response.json();--}}
            {{--        })--}}
            {{--        .then((data) => {--}}
            {{--            if (data.success) {--}}
            {{--                // Close modal--}}
            {{--                $('#editinvoiceModal').modal('hide').removeClass('show').addClass('fade');--}}
            {{--                $('body').removeClass('modal-open');--}}
            {{--                $('body').css('padding-right', '');--}}
            {{--                $('.modal-backdrop').remove();--}}

            {{--                // Success message--}}
            {{--                Swal.fire('Success', 'invoice updated successfully.', 'success');--}}

            {{--                // Reset form--}}
            {{--                form.reset();--}}

            {{--                // Reload DataTable--}}
            {{--                table.ajax.reload();--}}
            {{--            } else {--}}
            {{--                console.error('Server validation errors:', data.errors); // Log server errors--}}
            {{--                handleValidationErrors(form, data.errors);--}}
            {{--            }--}}
            {{--        })--}}
            {{--        .catch((error) => {--}}
            {{--            if (error.errors) {--}}
            {{--                // Handle validation errors--}}
            {{--                handleValidationErrors(form, error.errors);--}}
            {{--            } else {--}}
            {{--                // Log unexpected errors--}}
            {{--                console.error('Unexpected error:', error);--}}
            {{--                Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');--}}
            {{--            }--}}
            {{--        })--}}
            {{--        .finally(() => {--}}
            {{--            // Re-enable submit button and reinvoice text--}}
            {{--            submitButton.disabled = false;--}}
            {{--            submitButton.innerHTML = '<i class="fas fa-save"></i> Save Changes';--}}
            {{--        });--}}
            {{--});--}}

            {{--// Delete invoice Confirmation--}}
            {{--$(document).on('submit', 'form.delete', function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    Swal.fire({--}}
            {{--        title: 'Are you sure?',--}}
            {{--        text: "This action cannot be undone!",--}}
            {{--        icon: 'warning',--}}
            {{--        showCancelButton: true,--}}
            {{--        confirmButtonColor: '#d33',--}}
            {{--        cancelButtonColor: '#3085d6',--}}
            {{--        confirmButtonText: 'Yes, delete it!'--}}
            {{--    }).then((result) => {--}}
            {{--        if (result.isConfirmed) {--}}
            {{--            this.submit();--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            // Create invoice Form Submission
            {{--const invoiceinvoiceRoute = "{{ route('invoices.store') }}";--}}
            {{--document.getElementById('createinvoiceForm').addEventListener('submit', function (event) {--}}
            {{--    event.preventDefault();--}}
            {{--    const form = this;--}}
            {{--    const submitButton = form.querySelector('.submit-creating-form');--}}
            {{--    const formData = new FormData(form);--}}

            {{--    const data = {};--}}
            {{--    formData.forEach((value, key) => {--}}
            {{--        data[key] = value;--}}
            {{--    });--}}

            {{--    disableButton(submitButton, true);--}}

            {{--    fetch(invoiceinvoiceRoute, {--}}
            {{--        method: 'POST',--}}
            {{--        body: JSON.stringify(data),--}}
            {{--        headers: {--}}
            {{--            'Content-Type': 'application/json',--}}
            {{--            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),--}}
            {{--            'Accept': 'application/json',--}}
            {{--        },--}}
            {{--    })--}}
            {{--        .then(response => {--}}
            {{--            if (!response.ok) {--}}
            {{--                return response.json().then(errorData => {--}}
            {{--                    throw errorData;--}}
            {{--                });--}}
            {{--            }--}}
            {{--            return response.json();--}}
            {{--        })--}}
            {{--        .then(data => {--}}
            {{--            if (data.success) {--}}
            {{--                $('#createinvoiceModal').modal('hide').removeClass('show').addClass('fade');--}}
            {{--                $('body').removeClass('modal-open');--}}
            {{--                $('body').css('padding-right', '');--}}
            {{--                $('.modal-backdrop').remove();--}}

            {{--                Swal.fire('Success', 'invoice created successfully.', 'success');--}}
            {{--                form.reset();--}}
            {{--                table.ajax.reload();--}}
            {{--            } else {--}}
            {{--                handleValidationErrors(form, data.errors);--}}
            {{--            }--}}
            {{--        })--}}
            {{--        .catch(error => {--}}
            {{--            Swal.fire('Error', 'An unexpected error occurred.', 'error');--}}
            {{--        })--}}
            {{--        .finally(() => disableButton(submitButton, false));--}}
            {{--});--}}

            {{--// Utility Functions--}}
            {{--function resetForm(form) {--}}
            {{--    form.reset();--}}
            {{--    form.querySelectorAll('.is-invalid').forEach(input => input.classList.remove('is-invalid'));--}}
            {{--    form.querySelectorAll('.invalid-feedback').forEach(error => error.remove());--}}
            {{--}--}}

            {{--function handleValidationErrors(form, errors) {--}}
            {{--    for (let key in errors) {--}}
            {{--        const input = form.querySelector(`[name="${key}"]`);--}}
            {{--        if (input) {--}}
            {{--            input.classList.add('is-invalid');--}}
            {{--            let errorFeedback = input.nextElementSibling || document.createElement('div');--}}
            {{--            errorFeedback.className = 'invalid-feedback';--}}
            {{--            errorFeedback.innerText = errors[key][0];--}}
            {{--            input.insertAdjacentElement('afterend', errorFeedback);--}}
            {{--        }--}}
            {{--    }--}}
            {{--}--}}



            {{--function disableButton(button, disable) {--}}
            {{--    if (disable) {--}}
            {{--        button.disabled = true;--}}
            {{--        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';--}}
            {{--    } else {--}}
            {{--        button.disabled = false;--}}
            {{--        button.innerHTML = 'Submit';--}}
            {{--    }--}}
            {{--}--}}
        });

    </script>
    @endpush
