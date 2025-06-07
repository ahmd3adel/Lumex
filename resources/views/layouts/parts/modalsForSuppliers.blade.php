
<!-- View Supplier Modal -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Supplier Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> <span id="view-supplier-name"></span></p>
                        <p><strong>Company:</strong> <span id="view-supplier-company"></span></p>
                        <p><strong>Phone:</strong> <span id="view-supplier-phone"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Store:</strong> <span id="view-supplier-store"></span></p>
                        <p><strong>Created At:</strong> <span id="view-supplier-created"></span></p>
                        <p><strong>Updated At:</strong> <span id="view-supplier-updated"></span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p><strong>Address:</strong> <span id="view-supplier-address"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>   
<!-- end show store Modal -->
<!-- start create supplier Modal -->
<!-- start create supplier Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createSupplierModalLabel">
                    <i class="fas fa-user-plus"></i> {{ trans('Create New Supplier') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="createSupplierForm" action="{{ route('suppliers.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ trans('name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ trans('Enter name') }}">
                            <div id="name-error" class="invalid-feedback"></div>
                        </div>
                        
                        <!-- Company Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="company_name" class="form-label">{{ trans('company name') }}</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="{{ trans('Company name') }}" required>
                            <div id="company_name-error" class="invalid-feedback"></div>
                        </div>
                        
                        @if(!Auth::user()->hasRole('agent'))
                        <!-- Store Field -->
                        <div id="storeFieldWrapper" class="col-md-6 mb-3">
                            <label for="store" class="form-label">{{ trans('store') }}</label>
                            <select class="form-control" id="store_id" name="store_id">
                                <option value="">Select store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                            <div id="store_id-error" class="invalid-feedback"></div>
                        </div>
                        @endif
                        
                        <!-- Address Field -->
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">{{ trans('address') }}</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="{{ trans('Enter address') }}">
                            <div id="address-error" class="invalid-feedback"></div>
                        </div>
                        
                        <!-- Phone Field -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">{{ trans('phone') }}</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="{{ trans('Enter phone') }}">
                            <div id="phone-error" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> {{ trans('Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary submit-creating-form">
                        <i class="fas fa-save"></i> {{ trans('Create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end create supplier Modal -->

<!-- start edit store Modal -->

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">{{ trans('Edit Supplier') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="editSupplierForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit-supplier-id" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-supplier-name" class="form-label">{{ trans('name') }}</label>
                            <input type="text" class="form-control" id="edit-supplier-name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="edit-supplier-company" class="form-label">{{ trans('company name') }}</label>
                            <input type="text" class="form-control" id="edit-supplier-company" name="company_name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        @if(!Auth::user()->hasRole('agent'))
                        <div class="col-md-6 mb-3">
                            <label for="edit-supplier-store" class="form-label">{{ trans('store') }}</label>
                            <select class="form-control" id="edit-supplier-store" name="store_id">
                                <option value="">Select store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        @endif
                        
                        <div class="col-md-6 mb-3">
                            <label for="edit-supplier-phone" class="form-label">{{ trans('phone') }}</label>
                            <input type="text" class="form-control" id="edit-supplier-phone" name="phone">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="edit-supplier-address" class="form-label">{{ trans('address') }}</label>
                            <textarea class="form-control" id="edit-supplier-address" name="address" rows="2"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('Close') }}</button>
                    <button type="submit" class="btn btn-warning">{{ trans('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- end edit store Modal -->