@extends('layouts.index')
@section('title', trans($pageTitle))
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
                        <h1 class="">{{trans('supplier_invoices_table')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                            <li class="breadcrumb-item active "> {{trans('supplier_invoices')}} </li>
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
                <h3 class="card-title">{{__('supplier_invoice_table')}}</h3>
                <div class="card-tools">
                    <a class="btn btn-primary" href="{{route('supplier_invoices.create')}}">
                        <i class="fas fa-file-invoice-plus"></i> @lang('add_supplier_invoice')
                    </a>
                </div>
            </div>
            <!-- /.card-header -->

            <div class="table-responsive">
                <table id="supplier-invoice-table" class="table table-bordered table-hover w-100">
                    <thead>
                        <tr>
                            <th class="text-center"><i></i> {{ trans('id') }}</th>
                            <th class="text-center">{{ trans('invoice_no') }}</th>
                            <th class="text-center">{{ trans('supplier') }}</th>
                            <th class="text-center">{{ trans('store') }}</th>
                            <th class="text-center">{{ trans('net_total') }}</th>
                            <th class="text-center">{{ trans('invoice_date') }}</th>
                            <th class="text-center">{{ trans('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- سيتم تحميل البيانات ديناميكيًا -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
    </div>

    <!-- Modal لعرض تفاصيل الفاتورة -->
    <div class="modal fade" id="supplierInvoiceModal" tabindex="-1" aria-labelledby="supplierInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierInvoiceModalLabel">@lang('invoice_details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>@lang('invoice_no'):</strong> <span id="modal-supplier-invoice-no"></span></p>
                            <p><strong>@lang('supplier'):</strong> <span id="modal-supplier-invoice-supplier"></span></p>
                            <p><strong>@lang('store'):</strong> <span id="modal-supplier-invoice-store"></span></p>
                            <p><strong>@lang('total'):</strong> <span id="modal-supplier-invoice-total"></span></p>
                            <p><strong>@lang('discount'):</strong> <span id="modal-supplier-invoice-discount"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>@lang('net_total'):</strong> <span id="modal-supplier-invoice-net-total"></span></p>
                            <p><strong>@lang('pieces_no'):</strong> <span id="modal-supplier-invoice-pieces-no"></span></p>
                            <p><strong>@lang('invoice_date'):</strong> <span id="modal-supplier-invoice-date"></span></p>
                            <p><strong>@lang('notes'):</strong> <span id="modal-supplier-invoice-notes"></span></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>@lang('created_at'):</strong> <span id="modal-supplier-invoice-created-at"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>@lang('updated_at'):</strong> <span id="modal-supplier-invoice-updated-at"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('close')</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لتعديل الفاتورة -->
    <div class="modal fade" id="editSupplierInvoiceModal" tabindex="-1" aria-labelledby="editSupplierInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierInvoiceModalLabel">@lang('edit_invoice')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editSupplierInvoiceForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-invoice-no">@lang('invoice_no')</label>
                                    <input type="text" class="form-control" id="edit-invoice-no" name="invoice_no" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit-supplier-id">@lang('supplier')</label>
                                    <select class="form-control" id="edit-supplier-id" name="supplier_id" required>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit-store-id">@lang('store')</label>
                                    <select class="form-control" id="edit-store-id" name="store_id" required>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-total">@lang('total')</label>
                                    <input type="number" step="0.01" class="form-control" id="edit-total" name="total" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit-discount">@lang('discount')</label>
                                    <input type="number" step="0.01" class="form-control" id="edit-discount" name="discount">
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit-net-total">@lang('net_total')</label>
                                    <input type="number" step="0.01" class="form-control" id="edit-net-total" name="net_total" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-pieces-no">@lang('pieces_no')</label>
                                    <input type="number" class="form-control" id="edit-pieces-no" name="pieces_no">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-invoice-date">@lang('invoice_date')</label>
                                    <input type="date" class="form-control" id="edit-invoice-date" name="invoice_date" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit-notes">@lang('notes')</label>
                            <textarea class="form-control" id="edit-notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary submit-editing-form">
                            <i class="fas fa-save"></i> @lang('save_changes')
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('cssModal')
    <link rel="stylesheet" href="{{asset('dist/css/myCustomTable.css')}}">
@endpush

@push('jsModal')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const baseEditUrl = "{{ url('supplier_invoices') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>


    <script>
        $(document).ready(function () {
            $('#createSupplierInvoiceModal, #editSupplierInvoiceModal').on('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) resetForm(form);
            });

            const userIsNotAgent = {{ Auth::user()->hasRole('agent') ? 'false' : 'true' }};

            // Initialize DataTable for supplier invoices
            let table = $('#supplier-invoice-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('supplier_invoices.index') }}",
                    type: "GET",
                    error: function(xhr, error, thrown) {
                        console.error("Ajax error:", xhr.responseText);
                        $('#supplier-invoice-table_processing').hide();
                        Swal.fire('Error', 'Failed to load data', 'error');
                    }
                },
                columns: [
                    { data: 'id', name: 'id', className: 'text-center' },
                    { data: 'invoice_no', name: 'invoice_no', className: 'text-center' },
                    {
                        data: 'supplier.name',
                        name: 'supplier.name',
                        className: 'text-center',
                        defaultContent: '—'
                    },
                    {
                        data: 'store.name',
                        name: 'store.name',
                        className: 'text-center',
                        defaultContent: '—'
                    },
                    {
                        data: 'net_total',
                        name: 'net_total',
                        className: 'text-center',
                        render: data => `<span class="badge bg-info">${data}</span>`
                    },
                    { data: 'invoice_date', name: 'invoice_date', className: 'text-center' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
render: function(data, type, row) {
    return `
        <button class="btn btn-sm btn-info view-supplier-invoice"
            data-id="${row.id}"
            data-invoice-no="${row.invoice_no}"
            data-supplier="${row.supplier?.name}"
            data-store="${row.store?.name}"
            data-total="${row.total}"
            data-discount="${row.discount}"
            data-net-total="${row.net_total}"
            data-pieces-no="${row.pieces_no}"
            data-invoice-date="${row.invoice_date}"
            data-notes="${row.notes}"
            data-created="${row.created_at}"
            data-updated="${row.updated_at}">
            <i class="fas fa-eye"></i>
        </button>
<a href="supplier_invoices/${row.id}/edit" class="btn btn-sm btn-primary">
    <i class="fas fa-edit"></i>
</a>


        <form class="d-inline delete-supplier-invoice" method="POST" action="/supplier_invoices/${row.id}" onsubmit="return confirm('هل أنت متأكد من حذف الفاتورة؟')">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-sm btn-danger">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    `;
}


                    }
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

            // View supplier invoice Modal
            $(document).on('click', '.view-supplier-invoice', function() {
                const id = $(this).data('id');
                const invoiceNo = $(this).data('invoice-no');
                const supplier = $(this).data('supplier');
                const store = $(this).data('store');
                const total = $(this).data('total');
                const discount = $(this).data('discount');
                const netTotal = $(this).data('net-total');
                const piecesNo = $(this).data('pieces-no');
                const invoiceDate = $(this).data('invoice-date');
                const notes = $(this).data('notes');
                const created = $(this).data('created');
                const updated = $(this).data('updated');

                $('#modal-supplier-invoice-no').text(invoiceNo);
                $('#modal-supplier-invoice-supplier').text(supplier);
                $('#modal-supplier-invoice-store').text(store);
                $('#modal-supplier-invoice-total').text(total);
                $('#modal-supplier-invoice-discount').text(discount);
                $('#modal-supplier-invoice-net-total').text(netTotal);
                $('#modal-supplier-invoice-pieces-no').text(piecesNo);
                $('#modal-supplier-invoice-date').text(invoiceDate);
                $('#modal-supplier-invoice-notes').text(notes);
                $('#modal-supplier-invoice-created-at').text(created);
                $('#modal-supplier-invoice-updated-at').text(updated);

                $('#supplierInvoiceModal').modal('show');
            });

            // Edit supplier invoice Modal
            $(document).on('click', '.edit-supplier-invoice', function() {
                const invoiceId = $(this).data('id');
                const invoiceNo = $(this).data('invoice-no');
                const supplierId = $(this).data('supplier-id');
                const storeId = $(this).data('store-id');
                const total = $(this).data('total');
                const discount = $(this).data('discount');
                const netTotal = $(this).data('net-total');
                const piecesNo = $(this).data('pieces-no');
                const invoiceDate = $(this).data('invoice-date');
                const notes = $(this).data('notes');

                $('#editId').val(invoiceId);
                $('#edit-invoice-no').val(invoiceNo);
                $('#edit-supplier-id').val(supplierId);
                $('#edit-store-id').val(storeId);
                $('#edit-total').val(total);
                $('#edit-discount').val(discount);
                $('#edit-net-total').val(netTotal);
                $('#edit-pieces-no').val(piecesNo);
                $('#edit-invoice-date').val(invoiceDate);
                $('#edit-notes').val(notes);

                // Update form action
                $('#editSupplierInvoiceForm').attr('action', '/supplier_invoices/' + invoiceId);

                $('#editSupplierInvoiceModal').modal('show');
            });

            // Edit supplier invoice Form Submission
            $('#editSupplierInvoiceForm').on('submit', function (e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);

                // Convert FormData to JSON object
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                // Disable submit button and add spinner
                const submitButton = form.querySelector('.submit-editing-form');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                $.ajax({
                    url: form.action,
                    type: 'PUT',
                    data: data,
                    success: function (data) {
                        if (data.success) {
                            // Close modal
                            $('#editSupplierInvoiceModal').modal('hide');

                            // Success message
                            Swal.fire('Success', 'Supplier invoice updated successfully.', 'success');

                            // Reset form
                            form.reset();

                            // Reload DataTable
                            table.ajax.reload();
                        } else {
                            console.error('Server validation errors:', data.errors);
                            handleValidationErrors(form, data.errors);
                        }
                    },
                    error: function (error) {
                        if (error.responseJSON && error.responseJSON.errors) {
                            // Handle validation errors
                            handleValidationErrors(form, error.responseJSON.errors);
                        } else {
                            // Log unexpected errors
                            console.error('Unexpected error:', error);
                            Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
                        }
                    },
                    complete: function () {
                        // Re-enable submit button and reset text
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                    }
                });
            });

            // Delete supplier invoice Confirmation
            $(document).on('submit', 'form.delete-supplier-invoice', function (e) {
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
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'DELETE',
                            success: function (data) {
                                if (data.success) {
                                    Swal.fire('Deleted!', 'The invoice has been deleted.', 'success');
                                    table.ajax.reload();
                                } else {
                                    Swal.fire('Error!', 'Failed to delete the invoice.', 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error!', 'An error occurred while deleting.', 'error');
                            }
                        });
                    }
                });
            });

            // Utility Functions
            function resetForm(form) {
                form.reset();
                $(form).find('.is-invalid').removeClass('is-invalid');
                $(form).find('.invalid-feedback').remove();
            }

            function handleValidationErrors(form, errors) {
                for (let key in errors) {
                    const input = $(form).find(`[name="${key}"]`);
                    if (input.length) {
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
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