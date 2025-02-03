@extends('layouts.index')


@section('title', 'Store Details')

@section('breadcramp')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="">{{ trans('Store Details') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('stores.index') }}">{{ trans('Stores') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('Store Details') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('content')
            <div class="container">
                <div class="card p-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i> Client Transactions - {{ $client->name }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Reference No</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $balance = 0; // المتغير لحساب الرصيد المتراكم
                            @endphp
                            @foreach($transactions as $transaction)
                                @php
                                    $balance += $transaction['amount'];
                                @endphp
                                <tr>
                                    <td>
                                        @if($transaction['type'] == 'invoice')
                                            <span class="badge bg-success">Invoice</span>
                                        @elseif($transaction['type'] == 'return')
                                            <span class="badge bg-warning">Return</span>
                                        @elseif($transaction['type'] == 'payment')
                                            <span class="badge bg-danger">Payment</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction['reference_no'] }}</td>
                                    <td>${{ number_format($transaction['amount'], 2) }}</td>
                                    <td>${{ number_format($balance, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('d M, Y') }}</td>
                                    <td>
                                        @if($transaction['type'] == 'invoice')
                                            <a href="{{ route('invoices.show', $transaction['id']) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View Invoice
                                            </a>
                                        @elseif($transaction['type'] == 'return')
                                            <a href="{{ route('returns.show', $transaction['id']) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-eye"></i> View Return
                                            </a>
                                        @elseif($transaction['type'] == 'payment')
                                            <a href="{{ route('receipts.show', $transaction['id']) }}" class="btn btn-danger btn-sm">
                                                <i class="fas fa-eye"></i> View Payment
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Clients
                    </a>
                </div>
            </div>
@endsection

