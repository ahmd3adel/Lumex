<!-- start show client Modal -->
<div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="clientModalLabel">
                    <i class="fas fa-user-circle"></i> Client Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="card card-primary card-outline shadow-none">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-user"></i> {{trans('Name')}}:</strong>
                                    <span id="modal-client-name" class="text-primary"></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-envelope"></i> {{trans('Company Name')}}:</strong>
                                    <span id="modal-client-companyName" class="text-primary"></span>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-briefcase"></i> {{trans('Website')}}:</strong>
                                    <span id="modal-client-website" class="text-primary"></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-calendar-alt"></i> {{trans('Last Login')}}:</strong>
                                    <span id="modal-last_login" class="text-primary"></span>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-money-bill"></i> {{trans('Balance')}}:</strong>
                                    <span id="modal-client-balance" class="text-primary"></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-phone"></i> {{trans('Phone')}}:</strong>
                                    <span id="modal-phone" class="text-primary"></span>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-map-marker-alt"></i> {{trans('Address')}}:</strong>
                                    <span id="modal-address" class="text-primary"></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-id-badge"></i> User ID:</strong>
                                    <span id="modal-user" class="text-primary"></span>
                                </p>
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
<!-- end show client Modal -->
<!-- start create client Modal -->
<div class="modal fade" id="createclientModal" tabindex="-1" aria-labelledby="createclientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createclientModalLabel">
                    <i class="fas fa-user-plus"></i> {{ trans('Create New Client') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="createclientForm" action="{{ route('clients.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Section 1: Identity Details -->

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ trans('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ trans('Enter name') }}">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="company_name" class="form-label">{{ trans('Company Name') }}</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="{{ trans('Company name') }}" required>
                            @error('clientname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
@if(!Auth::user()->hasRole('agent'))
                        <div id="storeFieldWrapper" class="col-md-6 mb-3">
                            <label for="store" class="form-label">{{ trans('Store') }}</label>
                            <select class="form-control" name="store_id">
                                <option>Select store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <!-- Section 2: Contact Details -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">{{ trans('Phone') }}</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="{{ trans('Enter phone number') }}">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="clientwebsite" class="form-label">{{ trans('Website') }}</label>--}}
{{--                            <input type="text" class="form-control" id="client_website" name="website" placeholder="{{ trans('Enter website link') }}" required>--}}
{{--                            @error('website')--}}
{{--                            <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                            @enderror--}}
{{--                        </div>--}}

                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">{{ trans('Title') }}</label>
                            <input type="text" class="form-control" id="title" name="address" placeholder="{{ trans('Enter title') }}" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> {{ trans('Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary submit-creating-form" onclick="disableButton(this)">
                        <i class="fas fa-save"></i> {{ trans('Create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end create client Modal -->
<!-- start edit client Modal -->
<div class="modal fade" id="editclientModal" tabindex="-1" aria-labelledby="editclientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editclientModalLabel"><i class="fas fa-edit"></i> Edit Client</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <form id="editclientForm" action="" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Spoofing PUT method -->
                <div class="modal-body">
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-name" class="form-label">{{ trans('Name') }}</label>
                            <input type="text" class="form-control" id="edit-name" name="name" placeholder="Enter name" required>
                            <div class="invalid-feedback">Please enter a valid name.</div>
                        </div>

                        <input type="hidden" class="form-control" id="edit-id" name="id">

                        <!-- Title Field -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-title" class="form-label">{{ trans('Company name') }}</label>
                            <input type="text" class="form-control" id="edit-company-name" name="Company_name" placeholder="Enter Company name">
                            @error('Company_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Phone Field -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-phone" class="form-label">{{ trans('Phone') }}</label>
                            <input type="text" class="form-control" id="edit-phone" name="phone" placeholder="Enter phone number">
                            @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Website Field -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-website" class="form-label">{{ trans('Website') }}</label>
                            <input type="url" class="form-control" id="edit-website" name="website" placeholder="Enter website URL">
                            @error('website')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                                        <div class="row">
                                            <!-- Logo Field -->
                                            <div class="col-md-6 mb-3">
                                                <label for="edit-title" class="form-label">{{ trans('Title') }}</label>
                                                <input type="text" class="form-control" id="edit-address" name="address" placeholder="Enter Title">
                                                @error('address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="submit" class="btn btn-warning submit-editing-form" onclick="disableButton(this)">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- end edit client Modal -->
