<!-- start show product Modal -->
<!-- Show Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="productModalLabel"><i class="fas fa-box"></i> Product Details</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{trans('name')}}</strong> <span id="modal-product-name" class="text-primary"></span></p>
                        <p><strong>Price:</strong> $<span id="modal-product-price" class="text-primary"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Quantity:</strong> <span id="modal-product-quantity" class="text-primary"></span></p>
                        <p><strong>Cutter:</strong> <span id="modal-product-cutter" class="text-primary"></span></p>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description"></textarea>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end show product Modal -->
<!-- start create product Modal -->
<!-- Create Product Modal -->
<div class="modal fade" id="createproductModal" tabindex="-1" aria-labelledby="createproductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createproductModalLabel"><i class="fas fa-plus-circle"></i> Create New Product</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <form id="createproductForm" action="{{ route('products.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{trans('name')}}</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ trans('Enter product name') }}" required>
                        </div>
                        <!-- SKU Field -->
                        <!-- Price Field -->
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">{{trans('Price')}}</label>
                            <input type="number" class="form-control" id="price" name="price" placeholder="Enter product price" step="0.01" required>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Quantity Field -->
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">{{trans('Quantity')}}</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter product quantity" required>
                        </div>
{{--                        <div id="storeFieldWrapper" class="col-md-6 mb-3">--}}
{{--                            <label for="store" class="form-label">{{ trans('Store') }}</label>--}}
{{--                            <select class="form-control" name="store_id">--}}
{{--                                <option>Select store</option>--}}
{{--                                @foreach($stores as $store)--}}
{{--                                    <option value="{{ $store->id }}">{{ $store->name }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
                    </div>
                    <div class="row">
                        <!-- Description Field -->
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">{{trans('Discription')}}</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-primary submit-creating-form"><i class="fas fa-save"></i> Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end create product Modal -->
<!-- start edit product Modal -->

<!-- Edit Product Modal -->
<div class="modal fade" id="editproductModal" tabindex="-1" aria-labelledby="editproductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editproductModalLabel"><i class="fas fa-edit"></i> Edit Product</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <form id="editproductForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden"  id="edit-id" name="id">

                <div class="modal-body">
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name" placeholder="Enter product name" required>
                        </div>
                        <!-- SKU Field -->
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="edit-sku" class="form-label">Cutter Name</label>--}}
{{--                            <input type="text" class="form-control" id="edit-cutter_name" name="cutter_name" placeholder="Enter cutter name" required>--}}
{{--                        </div>--}}
                    </div>
                    <div class="row">
                        <!-- Price Field -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="edit-price" name="price" placeholder="Enter product price"  required>
                        </div>
                        <!-- Quantity Field -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit-quantity" name="quantity" placeholder="Enter product quantity" required>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Description Field -->
                        <div class="col-md-12 mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-description" name="description" rows="3" placeholder="Enter product description"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- end edit product Modal -->
