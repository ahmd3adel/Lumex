@extends('layouts.index')
@section('title', $pageTitle . ' PAGE')
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
                            <li class="breadcrumb-item active "> {{trans('invoice table')}} </li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        @endsection

        @section('content')
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-file-invoice"></i> Add Invoice</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('invoices.store')}}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Invoice Number -->
                            <div class="col-lg-6 mb-3">
                                <label for="invoice_no" class="form-label">Invoice Number</label>
                                <input type="text" id="invoice_no" name="invoice_no" class="form-control" placeholder="Enter invoice number" required>
                            </div>

                            <!-- Invoice Date -->
                            <div class="col-lg-6 mb-3">
                                <label for="invoice_date" class="form-label">Invoice Date</label>
                                <input type="date" id="invoice_date" name="invoice_date" class="form-control" required>
                            </div>

                            <!-- Client -->
                            <div class="col-lg-6 mb-3">
                                <label for="client_id" class="form-label">Client</label>
                                <select id="client_id" name="client_id" class="form-control" required>
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{$client->id}}">{{$client->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Store -->
                            @if(!Auth::user()->hasRole('agent'))
                            <div class="col-lg-6 mb-3">
                                <label for="store_id" class="form-label">Store</label>
                                <select id="store_id" name="store_id" class="form-control" required>
                                    <option value="">Select Store</option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}">{{$store->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <!-- Discount -->
                            <div class="col-lg-6 mb-3">
                                <label for="discount" class="form-label">Discount</label>
                                <input type="text" id="discount" name="discount" class="form-control" placeholder="Enter discount">
                            </div>
                        </div>
                        <hr>

                        <!-- Product List -->
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="card-title"><i class="fas fa-box"></i> Add Products</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="product-list">
                                    <tr>
                                        <td>
                                            <select class="form-control" name="product_id[]">
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="quantity[]" class="form-control" placeholder="Enter quantity" required>
                                        </td>
                                        <td>
                                            <input type="number" name="price[]" class="form-control" placeholder="Enter price" required>
                                        </td>
                                        <td>
                                            <input type="number" name="subtotal[]" class="form-control" placeholder="Subtotal" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-product">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-success btn-sm add-product">
                                        <i class="fas fa-plus"></i> Add Product
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <!-- Invoice Summary -->
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h4 class="card-title"><i class="fas fa-receipt"></i> Invoice Summary</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Partial Total
                                        <span>$0.00</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Discount
                                        <span>$0.00</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Tax
                                        <span>$0.00</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                        Total
                                        <span>$0.00</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <input type="hidden" name="net_total" id="net_total" value="0">
                        <input type="hidden" name="total" id="total" value="0">

                        <div class="row mt-4">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Invoice
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productList = document.querySelector('#product-list');
            const discountInput = document.querySelector('#discount');
            const netTotalInput = document.querySelector('#net_total');
            const totalInput = document.querySelector('#total');

            // Update invoice totals
            function updateInvoiceTotals() {
                let partialTotal = 0;

                // Calculate partial total from product subtotals
                productList.querySelectorAll('tr').forEach(row => {
                    const subtotal = parseFloat(row.querySelector('[name="subtotal[]"]').value) || 0;
                    partialTotal += subtotal;
                });

                // Get discount and calculate total
                const discount = parseFloat(discountInput.value) || 0;
                const total = partialTotal - discount;

                // Update hidden inputs and UI
                totalInput.value = partialTotal.toFixed(2);
                netTotalInput.value = total.toFixed(2);

                document.querySelector('.list-group-item:nth-child(1) span').textContent = `$${partialTotal.toFixed(2)}`;
                document.querySelector('.list-group-item:nth-child(2) span').textContent = `$${discount.toFixed(2)}`;
                document.querySelector('.list-group-item:nth-child(4) span').textContent = `$${total.toFixed(2)}`;
            }

            // Update subtotal for a row
            productList.addEventListener('input', function (event) {
                if (event.target.name === 'quantity[]' || event.target.name === 'price[]') {
                    const row = event.target.closest('tr');
                    const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
                    const price = parseFloat(row.querySelector('[name="price[]"]').value) || 0;

                    const subtotal = quantity * price;
                    row.querySelector('[name="subtotal[]"]').value = subtotal.toFixed(2);

                    updateInvoiceTotals();
                }
            });

            // Add new product row
            document.querySelector('.add-product').addEventListener('click', () => {
                const newRow = `
            <tr>
                <td>
                    <select class="form-control" name="product_id[]" required>
                        <option value="">Select Product</option>
                        ${document.querySelector('[name="product_id[]"]').innerHTML}
                    </select>
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control" placeholder="Enter quantity" required>
                </td>
                <td>
                    <input type="number" name="price[]" class="form-control" placeholder="Enter price" required>
                </td>
                <td>
                    <input type="number" name="subtotal[]" class="form-control" placeholder="Subtotal" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-product">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
                productList.insertAdjacentHTML('beforeend', newRow);
            });

            // Remove product row
            productList.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-product') || event.target.closest('.remove-product')) {
                    const row = event.target.closest('tr');
                    if (row) {
                        row.remove();
                        updateInvoiceTotals();
                    }
                }
            });

            // Update totals when discount changes
            discountInput.addEventListener('input', updateInvoiceTotals);

            var myStore = document.getElementById('store_id');
            myStore.addEventListener('change' , function (){
                console.log(this.value)
                $.ajax({
                    {{--url: "{{ route('clients.getMyClients') }}/" + this.value,--}}
                    url:"{{url('accounts/clients/getMyClients')}}/" + this.value,
                    type: 'GET',
                    data: {
                    },
                    success: function (response) {
                        console.log(response.data)
                    },
                    error: function () {
                        // alert('faild')
                    }
                });
            });

        });

    </script>
@endpush
