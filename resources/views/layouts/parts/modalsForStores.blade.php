<!-- start show store Modal -->
<div class="modal fade" id="storeModal" tabindex="-1" role="dialog" aria-labelledby="storeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="storeModalLabel">
                    <i class="fas fa-store"></i> {{ trans('Store Details') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <!-- Store Information -->
                            <div class="col-md-6 mb-3">
                                <p>
                                    <strong><i class="fas fa-store"></i> {{ trans('Company') }}:</strong>
                                    <span id="modal-store-name" class="text-primary font-weight-bold"></span>
                                </p>
                                <p>
                                    <strong><i class="fas fa-map-marker-alt"></i> {{ trans('Location') }}:</strong>
                                    <span id="modal-store-location" class="text-primary font-weight-bold"></span>
                                </p>
                            </div>
                            <!-- Dates Information -->
                            <div class="col-md-6 mb-3">
                                <p>
                                    <strong><i class="fas fa-clock"></i> {{ trans('Created At') }}:</strong>
                                    <span id="modal-store-created-at" class="text-primary font-weight-bold"></span>
                                </p>
                                <p>
                                    <strong><i class="fas fa-sync-alt"></i> {{ trans('Updated At') }}:</strong>
                                    <span id="modal-store-updated-at" class="text-primary font-weight-bold"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> {{ trans('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- end show store Modal -->
<!-- start create store Modal -->
<div class="modal fade" id="createstoreModal" tabindex="-1" aria-labelledby="createstoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createstoreModalLabel"><i class="fas fa-store-plus"></i> Create New store</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="createstoreForm" action="{{ route('stores.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{trans('Name')}}</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <!-- Email Field -->
                        <div class="col-md-6 mb-3">
                            <label for="Location" class="form-label">{{trans('Location')}}</label>
                            <input type="text" class="form-control" id="Location" name="location" placeholder="Enter Location">
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
<!-- end create store Modal -->
<!-- start edit store Modal -->

<div class="modal fade" id="editstoreModal" tabindex="-1" aria-labelledby="editstoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editstoreModalLabel">
                    <i class="fas fa-edit"></i> Edit Store
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="editstoreForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="editId" name="id">

                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-name" class="form-label">{{ trans('Name') }}</label>
                            <input type="text" class="form-control" id="edit-name" name="name" placeholder="Enter name" required>
                            <div class="invalid-feedback">Please enter a valid name.</div>
                        </div>
                        <!-- Location Field -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-location" class="form-label">{{ trans('Location') }}</label>
                            <input type="text" class="form-control" id="edit-location" name="location" placeholder="Enter location" required>
                            <div class="invalid-feedback">Please enter a valid location.</div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="submit" class="btn btn-warning submit-editing-form" id="submitEditstoreForm">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- end edit store Modal -->
