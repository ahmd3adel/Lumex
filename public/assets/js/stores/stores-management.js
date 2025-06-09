/**
 * Stores Management Module
 * Handles CRUD operations for stores using DataTables and AJAX
 */

class StoresManagement {
    constructor() {
        this.table = null;
        this.initDataTable();
        this.bindEvents();
    }

    /**
     * Initialize DataTable with configurations
     */
    initDataTable() {
        this.table = $('#store-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('stores.index') }}",
                type: "GET"
            },
            columns: [
                { data: 'id', name: 'id', searchable: true },
                { data: 'name', name: 'name' },
                { data: 'location', name: 'location' },
                { data: 'users', name: 'users.name' },
                { 
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data) {
                        return new Date(data).toLocaleString();
                    }
                },
                { 
                    data: 'updated_at', 
                    name: 'updated_at',
                    render: function(data) {
                        return new Date(data).toLocaleString();
                    }
                },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false 
                }
            ],
            dom: '<"row d-flex align-items-center p-3"<"col-md-3 col-12"l><"col-md-6 col-12 text-md-end text-center"B><"col-md-3 col-12"f>>' +
                '<"row"<"col-md-12"t>>' +
                '<"row"<"col-md-6"i><"col-md-6"p>>',
            buttons: this.getDataTableButtons(),
            lengthMenu: [10, 25, 50, 100],
            language: this.getDataTableLanguage(),
            responsive: true
        });
    }

    /**
     * Get DataTable buttons configuration
     */
    getDataTableButtons() {
        return [
            {
                extend: 'pdfHtml5',
                text: '{{ trans("Export To PDF") }}',
                className: 'btn btn-danger btn-sm',
                orientation: 'portrait',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                customize: (doc) => {
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
                text: '{{ trans("Export to Excel") }}',
                className: 'btn btn-success btn-sm',
                exportOptions: { 
                    columns: [0, 1, 2, 3, 4, 5] 
                }
            },
            {
                extend: 'print',
                text: '{{ trans("Print") }}',
                className: 'btn btn-info btn-sm',
                exportOptions: { 
                    columns: [0, 1, 2, 3, 4, 5] 
                }
            }
        ];
    }

    /**
     * Get DataTable language configuration
     */
    getDataTableLanguage() {
        return {
            lengthMenu: "{{ trans('Show _MENU_ entries') }}",
            info: "{{ trans('Showing _START_ to _END_ of _TOTAL_ entries') }}",
            search: "",
            searchPlaceholder: "{{ trans('Search...') }}",
            paginate: {
                first: "{{ trans('First') }}",
                last: "{{ trans('Last') }}",
                next: "{{ trans('Next') }}",
                previous: "{{ trans('Previous') }}"
            }
        };
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Create store button
        $('#createStoreBtn').on('click', () => this.showCreateModal());
        
        // View store event delegation
        $(document).on('click', '.view-store', (e) => this.showViewModal(e));
        
        // Edit store event delegation
        $(document).on('click', '.edit-store', (e) => this.showEditModal(e));
        
        // Delete store form submission
        $(document).on('submit', 'form.delete', (e) => this.confirmDelete(e));
        
        // Create store form submission
        $('#createstoreForm').on('submit', (e) => this.handleCreateForm(e));
        
        // Edit store form submission
        $('#editstoreForm').on('submit', (e) => this.handleEditForm(e));
        
        // Modal hidden event
        $('#createstoreModal, #editstoreModal').on('hidden.bs.modal', (e) => this.resetModalForm(e.target));
    }

    /**
     * Show create store modal
     */
    showCreateModal() {
        $('#createstoreModal').modal('show');
    }

    /**
     * Show view store modal
     */
    showViewModal(event) {
        const element = event.currentTarget;
        const id = element.getAttribute('data-id');
        const name = element.getAttribute('data-name');
        const location = element.getAttribute('data-location');
        const created = element.getAttribute('data-created');
        const updated = element.getAttribute('data-updated');

        $('#modal-store-name').text(name);
        $('#modal-store-location').text(location);
        $('#modal-store-created-at').text(created);
        $('#modal-store-updated-at').text(updated);

        $('#storeModal').modal('show');
    }

    /**
     * Show edit store modal
     */
    showEditModal(event) {
        const element = event.currentTarget;
        const storeId = element.getAttribute('data-id');
        const name = element.getAttribute('data-name');
        const location = element.getAttribute('data-location');

        $('#editId').val(storeId);
        $('#edit-name').val(name);
        $('#edit-location').val(location);

        $('#editstoreModal').modal('show');
    }

    /**
     * Handle create store form submission
     */
    async handleCreateForm(event) {
        event.preventDefault();
        const form = event.target;
        const submitButton = form.querySelector('.submit-creating-form');
        
        try {
            this.toggleButtonState(submitButton, true);
            
            const response = await fetch("{{ route('stores.store') }}", {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw data;
            }
            
            if (data.success) {
                this.closeModal('createstoreModal');
                this.showSuccessAlert('{{ trans("Store created successfully") }}');
                form.reset();
                this.table.ajax.reload();
            } else {
                this.displayFormErrors(form, data.errors);
            }
        } catch (error) {
            this.handleAjaxError(error);
        } finally {
            this.toggleButtonState(submitButton, false);
        }
    }

    /**
     * Handle edit store form submission
     */
    async handleEditForm(event) {
        event.preventDefault();
        const form = event.target;
        const submitButton = form.querySelector('.submit-editing-form');
        
        try {
            this.toggleButtonState(submitButton, true);
            
            const formData = new FormData(form);
            const storeId = formData.get('id');
            const updateUrl = "{{ route('stores.update', ':id') }}".replace(':id', storeId);
            
            const response = await fetch(updateUrl, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw data;
            }
            
            if (data.success) {
                this.closeModal('editstoreModal');
                this.showSuccessAlert('{{ trans("Store updated successfully") }}');
                form.reset();
                this.table.ajax.reload();
            } else {
                this.displayFormErrors(form, data.errors);
            }
        } catch (error) {
            this.handleAjaxError(error);
        } finally {
            this.toggleButtonState(submitButton, false);
        }
    }

    /**
     * Confirm store deletion
     */
    confirmDelete(event) {
        event.preventDefault();
        const form = event.target;
        
        Swal.fire({
            title: '{{ trans("Are you sure?") }}',
            text: "{{ trans('This action cannot be undone!') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ trans("Yes, delete it!") }}',
            cancelButtonText: '{{ trans("Cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    /**
     * Reset modal form when hidden
     */
    resetModalForm(modal) {
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(input => 
                input.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(error => 
                error.remove());
        }
    }

    /**
     * Close modal and clean up
     */
    closeModal(modalId) {
        $(`#${modalId}`).modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }

    /**
     * Display form validation errors
     */
    displayFormErrors(form, errors) {
        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => 
            el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => 
            el.remove());
        
        // Display new errors
        for (const [field, messages] of Object.entries(errors)) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = messages[0];
                input.parentNode.appendChild(errorDiv);
            }
        }
    }

    /**
     * Handle AJAX errors
     */
    handleAjaxError(error) {
        console.error('Error:', error);
        
        if (error.errors) {
            // Validation errors are handled in the form submission methods
            return;
        }
        
        const message = error.message || '{{ trans("An unexpected error occurred") }}';
        this.showErrorAlert(message);
    }

    /**
     * Show success alert
     */
    showSuccessAlert(message) {
        Swal.fire({
            title: '{{ trans("Success") }}',
            text: message,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }

    /**
     * Show error alert
     */
    showErrorAlert(message) {
        Swal.fire({
            title: '{{ trans("Error") }}',
            text: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }

    /**
     * Toggle button state between loading and normal
     */
    toggleButtonState(button, isLoading) {
        if (isLoading) {
            button.disabled = true;
            button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${button.dataset.loadingText || 'Processing...'}`;
        } else {
            button.disabled = false;
            button.innerHTML = button.dataset.originalText;
        }
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    new StoresManagement();
});