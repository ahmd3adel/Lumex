@extends('layouts.index')
@section('title', $pageTitle . ' PAGE')

@section('breadcramp')
    <div class="content-wrapper">
        <div class="content-header py-2"> <!-- تم تعديل padding هنا -->
            <div class="container-fluid">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="card-tools">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createstoreModal">
                                <i class="fas fa-store-plus"></i> @lang('add store')
                            </button>
                        </div>                    <ol class="breadcrumb m-0 p-0 d-flex flex-wrap">
                        <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('store table')}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection


</style>

        @section('content')
        
            <div class="container-fluid">
                    <div class="content-wrapper">
                    <div class="table-responsive ">
                        <table id="store-table" class="table table-bordered table-hover w-100">
                            <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> {{ trans('id') }}</th>
                                <th><i class="fas fa-store"></i> {{ trans('name') }}</th>
                                <th><i class="fas fa-store"></i> {{ trans('location') }}</th>
                                <th><i class="fas fa-store"></i> {{ trans('Users') }}</th>
                                <th><i class="fas fa-store"></i> {{ trans('Created_at') }}</th>
                                <th><i class="fas fa-store"></i> {{ trans('Updated_at') }}</th>
                                <th><i class="fas fa-cogs"></i> {{ trans('actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    </div>



                </div>
                <!-- /.card -->
            </div>

        @include('layouts.parts.modalsForStores')

        @endsection

        @push('cssModal')
            {{-- <link rel="stylesheet" href="{{asset('dist/css/myCustomTable.css')}}"> --}}
        @endpush
@push('jsModal')
            <script src="{{ asset('dist/js/jquery.js') }}"></script>
            <script src="{{ asset('dist/js/sweetAlert2.js') }}"></script>
<script>
    $(document).ready(function () {
        // Initialize DataTable
        const table = initDataTable();
        
        // Setup modal event handlers
        setupModalHandlers(table);
        
        // Setup form submissions
        setupFormSubmissions(table);
        
        // Setup delete confirmation
        setupDeleteConfirmation();
    });

    // DataTable Initialization
    function initDataTable() {
        return $('#store-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ route('stores.index') }}",
            columns: [
                { data: 'id', name: 'id', searchable: true },
                { data: 'name', name: 'name' },
                { data: 'location', name: 'location' },
                { data: 'users', name: 'users.name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
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
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
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
    }

    // Modal Handlers
    function setupModalHandlers(table) {
        // View Store Modal
        document.addEventListener('click', function (e) {
            if (e.target.closest('.view-store')) {
                showViewModal(e.target);
            }
            
            if (e.target.closest('.edit-store')) {
                showEditModal(e.target);
            }
        });

        // Reset forms when modals are closed
        $('#createstoreModal, #editstoreModal').on('hidden.bs.modal', function () {
            resetForm(this.querySelector('form'));
        });
    }

    function showViewModal(element) {
        const id = element.getAttribute('data-id');
        const name = element.getAttribute('data-name');
        const location = element.getAttribute('data-location');
        const created = element.getAttribute('data-created');
        const updated = element.getAttribute('data-updated');

        document.getElementById('modal-store-name').innerText = name;
        document.getElementById('modal-store-location').innerText = location;
        document.getElementById('modal-store-created-at').innerText = created;
        document.getElementById('modal-store-updated-at').innerText = updated;

        const modal = new bootstrap.Modal(document.getElementById('storeModal'));
        modal.show();
    }

    function showEditModal(element) {
        const storeId = element.getAttribute('data-id');
        const name = element.getAttribute('data-name');
        const location = element.getAttribute('data-location');

        document.getElementById('editId').value = storeId;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-location').value = location;

        const modal = new bootstrap.Modal(document.getElementById('editstoreModal'));
        modal.show();
    }

    // Form Submissions
    function setupFormSubmissions(table) {
        // Edit Store Form
        document.getElementById('editstoreForm').addEventListener('submit', function (e) {
            e.preventDefault();
            submitForm(this, table, 'PUT', "{{ route('stores.update', ':id') }}");
        });

        // Create Store Form
        document.getElementById('createstoreForm').addEventListener('submit', function (e) {
            e.preventDefault();
            submitForm(this, table, 'POST', "{{ route('stores.store') }}");
        });
    }

    async function submitForm(form, table, method, route) {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const submitButton = form.querySelector('.submit-creating-form, .submit-editing-form');
        
        // Update route with ID for PUT requests
        const finalRoute = method === 'PUT' ? route.replace(':id', data.id) : route;
        
        try {
            disableButton(submitButton, true);
            
            const response = await fetch(finalRoute, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();
            
            if (!response.ok) throw result;

            if (result.success) {
                closeModal(form.closest('.modal').id);
                showSuccessAlert('Operation completed successfully');
                form.reset();
                table.ajax.reload();
            } else {
                handleValidationErrors(form, result.errors);
            }
        } catch (error) {
            if (error.errors) {
                handleValidationErrors(form, error.errors);
            } else {
                console.error('Error:', error);
                showErrorAlert('An error occurred. Please try again.');
            }
        } finally {
            disableButton(submitButton, false);
        }
    }

    // Delete Confirmation
    function setupDeleteConfirmation() {
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
                if (result.isConfirmed) this.submit();
            });
        });
    }

    // Utility Functions
    function resetForm(form) {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(input => input.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(error => error.remove());
    }

    function handleValidationErrors(form, errors) {
        for (const key in errors) {
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
            button.innerHTML = button.classList.contains('submit-editing-form') ? 
                '<i class="fas fa-save"></i> Save Changes' : 'Submit';
        }
    }

    function closeModal(modalId) {
        $(`#${modalId}`).modal('hide').removeClass('show').addClass('fade');
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
        $('.modal-backdrop').remove();
    }

    function showSuccessAlert(message) {
        Swal.fire('Success', message, 'success');
    }

    function showErrorAlert(message) {
        Swal.fire('Error', message, 'error');
    }
</script>
    @endpush
