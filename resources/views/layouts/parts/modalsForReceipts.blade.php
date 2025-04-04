<!-- start show user Modal -->
{{--@dd($clients)--}}
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
                                <p><strong><i class="fas fa-user"></i> {{trans('Name')}}:</strong> <span id="modal-user-name" class="text-primary"></span></p>
                                <p><strong><i class="fas fa-envelope"></i> {{trans('Email')}}:</strong> <span id="modal-user-email" class="text-primary"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-briefcase"></i> {{trans('Role')}}: </strong> <span id="modal-user-role" class="text-primary"></span></p>
                                <p><strong><i class="fas fa-calendar-alt"></i> Joined:</strong> <span id="modal-user-joined" class="text-primary"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-briefcase"></i> {{trans('Username')}}:</strong> <span id="modal-username" class="text-primary"></span></p>
                            </div>

                            <div class="col-md-6">
                                <p><strong><i class="fas fa-calendar-alt"></i> {{trans('Phone')}}:</strong> <span id="modal-phone" class="text-primary"></span></p>
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
<!-- end show user Modal -->
<!-- start create user Modal -->
<div class="modal fade" id="createreceiptModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createUserModalLabel"><i class="fas fa-user-plus"></i> Create New receipt</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="createReceiptForm" action="{{ route('receipts.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Voucher Number -->
                        <div class="col-md-6 mb-3">
                            <label for="voucher_no" class="form-label">{{ trans('Voucher No') }}</label>
                            <input type="text" class="form-control" id="voucher_no" name="voucher_no" placeholder="Enter Voucher Number">
                        </div>

                        <!-- Receipt Date -->
                        <div class="col-md-6 mb-3">
                            <label for="receipt_date" class="form-label">{{ trans('Receipt Date') }}</label>
                            <input type="date" class="form-control" id="receipt_date" name="receipt_date">
                        </div>

                        <!-- Client -->
                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label">{{ trans('Client') }}</label>
                            <select class="form-control" id="client_id" name="client_id" required>
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ ucfirst($client->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Store -->
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="store_id" class="form-label">{{ trans('Store') }}</label>--}}
{{--                            <select class="form-control" id="store_id" name="store_id" required>--}}
{{--                                <option value="">Select Store</option>--}}
{{--                                @foreach($stores as $store)--}}
{{--                                    <option value="{{ $store->id }}">{{ ucfirst($store->name) }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

                        <!-- Amount -->
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">{{ trans('Amount') }}</label>
                            <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter Amount" step="0.01" required>
                        </div>

                        <!-- Payment Method -->
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">{{ trans('Payment Method') }}</label>
                            <select class="form-control" id="payment_method" name="payment_method">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label">{{ trans('Notes') }}</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter any additional notes..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-primary submit-creating-form" onclick="disableButton(this)">
                        <i class="fas fa-save"></i> Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end create user Modal -->
<!-- start edit user Modal -->
{{--<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-lg">--}}
{{--        <div class="modal-content">--}}
{{--            <!-- Modal Header -->--}}
{{--            <div class="modal-header bg-warning text-white">--}}
{{--                <h5 class="modal-title" id="editUserModalLabel"><i class="fas fa-edit"></i> Edit User</h5>--}}
{{--                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}

{{--            <!-- Modal Body -->--}}
{{--            <form id="editUserForm" method="POST">--}}
{{--                @csrf--}}
{{--                @method('PUT')--}}
{{--                <div class="modal-body">--}}
{{--                    <input type="hidden" class="form-control" id="editId" name="id">--}}

{{--                    <div class="row">--}}
{{--                        <!-- Name Field -->--}}
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="edit-name" class="form-label">{{trans('Name')}}</label>--}}
{{--                            <input type="text" class="form-control" id="edit-name" name="name" placeholder="Enter name" required>--}}
{{--                            @error('name')--}}
{{--                            <div class="invalid-feedback">--}}
{{--                                {{ $message }}--}}
{{--                            </div>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
{{--                        <!-- Email Field -->--}}
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="edit-email" class="form-label">{{trans('Email')}}</label>--}}
{{--                            <input type="email" class="form-control" id="edit-email" name="email" placeholder="Enter email" required>--}}
{{--                            <div class="invalid-feedback">Please enter a valid email.</div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="edit-phone" class="form-label">{{trans('Phone')}}</label>--}}
{{--                            <input type="text" class="form-control" id="edit-phone" name="phone" placeholder="Enter phone number" required>--}}
{{--                            <div class="invalid-feedback">Please enter a valid phone number.</div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="edit-username" class="form-label">{{trans('Username')}}</label>--}}
{{--                            <input type="text" class="form-control" id="edit-username" name="username" placeholder="Enter username" required>--}}
{{--                            <div class="invalid-feedback">Please enter a valid username.</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row">--}}
{{--                        <!-- Role Field -->--}}
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="edit-role" class="form-label">{{trans('Role')}}</label>--}}
{{--                            <select class="form-control" id="edit-role" name="role" required>--}}
{{--                                <option value="">Select role</option>--}}
{{--                                @foreach($roles as $role)--}}
{{--                                    <option value="{{$role->name}}">{{ucfirst($role->name)}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            <div class="invalid-feedback">Please select a role.</div>--}}
{{--                        </div>--}}


{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="password" class="form-label">Password</label>--}}
{{--                            <input type="password" autocomplete="off" class="form-control" id="password" name="password" placeholder="Enter password">--}}
{{--                        </div>--}}

{{--                        <div id="storeFieldWrapper" class="col-md-6 mb-3">--}}
{{--                            <label for="store" class="form-label">{{ trans('Store') }}</label>--}}
{{--                            <select class="form-control" name="store_id">--}}
{{--                                <option>Select store</option>--}}
{{--                                @foreach($stores as $store)--}}
{{--                                    <option value="{{ $store->id }}">{{ $store->name }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Modal Footer -->--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>--}}
{{--                    <button type="submit" class="btn btn-warning submit-editing-form" id="submitEditUserForm"><i class="fas fa-save"></i> Save Changes</button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<!-- end edit user Modal -->

{{--<div class="modal fade" id="storeModal" tabindex="-1" role="dialog" aria-labelledby="storeModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog" role="document">--}}
{{--        <div class="modal-content">--}}
{{--            <!-- Modal Header -->--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="storeModalLabel">Store Details</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}

{{--            <!-- Modal Body -->--}}
{{--            <div class="modal-body">--}}
{{--                <p><strong>Name:</strong> <span id="modal-store-name"></span></p>--}}
{{--                <p><strong>Address:</strong> <span id="modal-store-location"></span></p>--}}
{{--            </div>--}}

{{--            <!-- Modal Footer -->--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


