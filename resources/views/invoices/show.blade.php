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
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">


                            <!-- Main content -->
                            <div class="invoice p-3 mb-3">
                                <div class="row invoice-info">
                                    <div class="col-sm-4 invoice-col">
                                        From
                                        <address>
                                            <strong>{{$inv->store->name}}</strong><br>
                                            {{$inv->store->location}}<br>
                                            San Francisco, CA 94107<br>
                                            Phone: (804) 123-5432<br>
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        To
                                        <address>
                                            <strong>{{$inv->client->company_name}}</strong><br>
                                            795 Folsom Ave, Suite 600<br>
                                            San Francisco, CA 94107<br>
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        <div class="col-sm-4 invoice-col">
                                            no
                                            <address>
                                        <b>Invoice {{$inv->invoice_no}}</b><br>
                                        <br>
                                        <b>Pieces</b>:</b> {{$inv->pieces_no}}<br>
                                            </address>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <!-- Table row -->
                                <div class="row w-100">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Product</th>
{{--                                                <th>Description</th>--}}
                                                <th>Subtotal</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($inv->products as $product)
                                                <tr>
                                                    <td>{{$product->quantity}}</td>
                                                    <td>{{$product->unit_price}}</td>
                                                    <td>{{$product->product->name}}</td>
{{--                                                    <td>{{$product->description}}</td>--}}
                                                    <td>{{$product->subtotal}}</td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">
                                    <!-- accepted payments column -->
                                    <div class="col-6">
{{--                                        <p class="lead">Payment Methods:</p>--}}
{{--                                        <img src="../../dist/img/credit/visa.png" alt="Visa">--}}
{{--                                        <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">--}}
{{--                                        <img src="../../dist/img/credit/american-express.png" alt="American Express">--}}
{{--                                        <img src="../../dist/img/credit/paypal2.png" alt="Paypal">--}}

                                        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                                            plugg
                                            dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                                        </p>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-6">
                                        <p class="lead">Amount Due 2/22/2014</p>

                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th style="width:50%">Subtotal:</th>
                                                    <td>{{$inv->total}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total:</th>
                                                    <td>{{$inv->net_total}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <!-- this row will not appear when printing -->
                                <div class="row no-print">
                                    <div class="col-12">
                                        <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                        <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                                            Payment
                                        </button>
                                        <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                            <i class="fas fa-download"></i> Generate PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.invoice -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
@endsection
