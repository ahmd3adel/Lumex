@extends('layouts.index')
@section('title', $pageTitle . ' PAGE')

@section('breadcramp')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-sm-6">
                    <h1>{{ trans('invoice') }}</h1>
                </div>
                <div class="col-sm-6">
                    @parent
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                        <li class="breadcrumb-item active"> edit deduction </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('content')
<div class="container">
    <h4 class="mb-4">تعديل خصم</h4>

    <form method="POST" action="{{ route('deductions.update', $deduction->id) }}">
        @csrf
        @method('PUT')

        <div class="row mb-4">
            @if(!Auth::user()->hasRole('agent'))
                <div class="col-md-6">
                    <label><strong>المتجر</strong></label>
                    <select class="form-control" name="store_id" required>
                        <option value="">اختر المتجر</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ $deduction->store_id == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-md-6">
                <label><strong>العميل</strong></label>
                <select class="form-control" name="client_id" required>
                    <option value="">اختر العميل</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $deduction->client_id == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label><strong>رقم الإذن</strong></label>
                <input type="text" name="voucher_no" class="form-control" value="{{ old('voucher_no', $deduction->voucher_no) }}">
            </div>
            <div class="col-md-6">
                <label><strong>قيمة الخصم</strong></label>
                <input type="number" step="0.01" name="amount" class="form-control" required value="{{ old('amount', $deduction->amount) }}">
            </div>
        </div>

        <div class="form-group mb-4">
            <label><strong>سبب الخصم</strong></label>
            <textarea name="reason" class="form-control" rows="3">{{ old('reason', $deduction->notes) }}</textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> تحديث
            </button>
        </div>
    </form>
</div>
@endsection
