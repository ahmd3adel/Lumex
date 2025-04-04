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
                            <i class="fas fa-store"></i> Store Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Store Name:</th>
                                <td>{{ $store->name }}</td>
                            </tr>
                        </table>

                        <!-- Display Store Logo if exists -->
                        @if($store->logo)
                            <div class="text-center mt-3">
                                <h5>Store Logo</h5>
                                <img src="{{ asset('storage/' . $store->logo) }}" alt="Store Logo" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Store Related Receipts -->
                <div class="card mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="card-title">
                            <i class="fas fa-receipt"></i> Related Receipts
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>{{trans('pieces')}}</th>
                                <th>{{trans('balance')}}</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($store->receipts as $receipt)
                                <tr>
                                    <td>{{ $receipt->id }}</td>
                                    <td>{{ $receipt->voucher_no }}</td>
                                    <td>{{ $receipt->client->name ?? 'N/A' }}</td>
                                    <td>${{ number_format($receipt->amount, 2) }}</td>
                                    <td>{{ $receipt->receipt_date->format('d M, Y') }}</td>
                                    <td>
                                        <a href="{{ route('receipts.show', $receipt->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No receipts found for this store.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-4">
                    <a href="{{ route('stores.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Stores
                    </a>
                </div>
            </div>
@endsection
