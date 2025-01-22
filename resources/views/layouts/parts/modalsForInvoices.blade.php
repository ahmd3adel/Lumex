<!-- Start show Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="invoiceModalLabel">
                    <i class="fas fa-file-invoice"></i> Invoice Details
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
                                <p><strong><i class="fas fa-hashtag"></i> Invoice No:</strong> <span id="modal-invoice-no" class="text-primary"></span></p>
                                <p><strong><i class="fas fa-calendar-alt"></i> Invoice Date:</strong> <span id="modal-invoice-date" class="text-primary"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-store"></i> Store:</strong> <span id="modal-invoice-store" class="text-primary"></span></p>
                                <p><strong><i class="fas fa-user"></i> Client:</strong> <span id="modal-invoice-client" class="text-primary"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-dollar-sign"></i> Total:</strong> <span id="modal-invoice-total" class="text-primary"></span></p>
                                <p><strong><i class="fas fa-percent"></i> Discount:</strong> <span id="modal-invoice-discount" class="text-primary"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-money-check-alt"></i> Net Total:</strong> <span id="modal-invoice-net-total" class="text-primary"></span></p>
                                <p><strong><i class="fas fa-sticky-note"></i> Notes:</strong> <span id="modal-invoice-notes" class="text-primary"></span></p>
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
<!-- End show Invoice Modal -->

<!-- Start create Invoice Modal -->
<div class="modal fade" id="createinvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal-width">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createInvoiceModalLabel"><i class="fas fa-file-invoice"></i> Create New Invoice</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="createInvoiceForm" action="{{ route('invoices.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" placeholder="Enter invoice number" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date</label>
                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label">Client</label>
                            <select class="form-control" id="client_id" name="client_id" required>
                                <option value="">Select client</option>
{{--                                @foreach($clients as $client)--}}
{{--                                    <option value="{{ $client->id }}">{{ $client->name }}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="store_id" class="form-label">Store</label>
                            <select class="form-control" id="store_id" name="store_id" required>
                                <option value="">Select store</option>
{{--                                @foreach($stores as $store)--}}
{{--                                    <option value="{{ $store->id }}">{{ $store->name }}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total" class="form-label">Total</label>
                            <input type="number" step="0.01" class="form-control" id="total" name="total" placeholder="Enter total amount" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="discount" class="form-label">Discount</label>
                            <input type="number" step="0.01" class="form-control" id="discount" name="discount" placeholder="Enter discount amount">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="net_total" class="form-label">Net Total</label>
                            <input type="number" step="0.01" class="form-control" id="net_total" name="net_total" placeholder="Enter net total" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter any additional notes"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End create Invoice Modal -->
