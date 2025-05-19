@extends('layouts.index')

@section('title', $pageTitle)
@section('styles')

<style>
    footer.main-footer {
        width: 100%
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .printable-area, .printable-area * {
            visibility: visible;
        }

        .d-print-none {
            display: none !important;
        }

        .d-print-block {
            display: block !important;
        }

        .table th, .table td {
            font-size: 14px !important;
            vertical-align: middle !important;
            padding: 6px 10px !important;
        }

        .table th:last-child,
        .table td:last-child {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .content-wrapper {
            padding-top: 0 !important;
        }

        .footer-section {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 20px;
        }

        .print-logo {
            max-width: 120px;
            margin: 10px auto;
            display: block;
        }

        .d-print-block h2, .d-print-block h4 {
            margin: 5px 0;
        }
    }

    .table td:last-child {
        white-space: nowrap;
        vertical-align: middle;
    }
</style>
@endsection

@section('breadcramp')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <h4 class="m-auto">{{ trans('Store Details') }} <span class="" style="color: #589eeb">{{ $client->name }}</span> </h4>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container printable-area">

<!-- رأس الصفحة عند الطباعة -->
<div class="d-print-block d-none mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <!-- اسم المصنع على اليمين -->
        <div class="text-end flex-grow-1">
            <h2 class="fw-bold m-0">مصنع: {{ Auth::user()->store->name }}</h2>
        </div>

        <!-- الشعار على اليسار -->
        <div class="text-start">
            <img src="{{ asset('logo.png') }}" alt="الشعار" style="max-width: 60px; height: auto;">
        </div>
    </div>

    <!-- اسم العميل في سطر مستقل -->
    <div class="text-center mt-2">
        <h4 class="fw-bold border-top pt-2">كشف حساب العميل: {{ $client->name }}</h4>
    </div>
</div>



<!-- أزرار العمليات -->
<!-- أزرار العمليات -->
<div class="mb-3 d-print-none">
    <a href="{{ route('invoices.create') }}?client_id={{ $client->id }}" class="btn btn-success me-2">
        <i class="fas fa-file-invoice-dollar"></i> إضافة فاتورة
    </a>
    <a href="{{ route('returns.create') }}?client_id={{ $client->id }}" class="btn btn-warning me-2">
        <i class="fas fa-undo"></i> إضافة مرتجع
    </a>
    <a href="{{ route('invoices.create') }}?client_id={{ $client->id }}" class="btn btn-danger">
        <i class="fas fa-money-bill-wave"></i> إضافة دفعة
    </a>
</div>



    <div class="card p-4">
        <div class="card-body">
            <table id="transactionsTable" class="table table-bordered table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>{{ trans('Reference No') }}</th>
                        <th>{{ trans('Pieces') }}</th>
                        <th>{{ trans('Invoice') }}</th>
                        <th>{{ trans('Return') }}</th>
                        <th>{{ trans('Payment') }}</th>
                        <th class="bg-primary text-white">{{ trans('Balance') }}</th>
                        <th>{{ trans('Date') }}</th>
                        <th class="d-print-none">{{ trans('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $balance = 0;
                        $totalInvoice = 0;
                        $totalReturn = 0;
                        $totalPayment = 0;
                        $totalInvoicePieces = 0;
                        $totalReturnPieces = 0;
                    @endphp
                    @foreach($transactions as $transaction)
                        @php
                            $balance += $transaction['amount'];
                            if ($transaction['type'] == 'invoice') {
                                $totalInvoice += $transaction['amount'];
                                $totalInvoicePieces += $transaction['pieces_no'];
                            } elseif ($transaction['type'] == 'return') {
                                $totalReturn += $transaction['amount'];
                                $totalReturnPieces += $transaction['pieces_no'];
                            } elseif ($transaction['type'] == 'payment') {
                                $totalPayment += $transaction['amount'];
                            }
                            $rowClass = match ($transaction['type']) {
                                'invoice' => 'table-success',
                                'return'  => 'table-warning',
                                'payment' => 'table-danger',
                                default   => '',
                            };
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td><strong>{{ $transaction['reference_no'] }}</strong></td>
                            <td class="fw-bold @if($transaction['type'] == 'invoice') text-success @elseif($transaction['type'] == 'return') text-warning @endif">
                                @if($transaction['type'] == 'invoice')
                                    +{{ number_format($transaction['pieces_no'], 0) }}
                                @elseif($transaction['type'] == 'return')
                                    -{{ number_format($transaction['pieces_no'], 0) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-success fw-bold">
                                @if ($transaction['type'] == 'invoice')
                                    <a href="{{ route('invoices.show', $transaction['id']) }}" class="text-success text-decoration-none">
                                        {{ number_format($transaction['amount'], 0) }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-warning fw-bold">
                                {{ $transaction['type'] == 'return' ? number_format($transaction['amount'], 0) : '-' }}
                            </td>
                            <td class="text-danger fw-bold">
                                {{ $transaction['type'] == 'payment' ? number_format($transaction['amount'], 0) : '-' }}
                            </td>
                            <td class="fw-bold bg-light">
                                {{ number_format($balance, 0) }}
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ \Carbon\Carbon::parse($transaction['date'])->format('d-m-Y') }}
                                </span>
                            </td>
                            <td class="d-print-none text-nowrap align-middle">
                                @if($transaction['type'] == 'invoice')
                                    <a href="{{ route('invoices.edit', $transaction['id']) }}" class="btn btn-sm btn-success" title="تعديل الفاتورة">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @elseif($transaction['type'] == 'return')
                                    <a href="{{ route('returns.edit', $transaction['id']) }}" class="btn btn-sm btn-warning" title="تعديل المرتجع">
                                        <i class="fas fa-exchange-alt"></i>
                                    </a>
                                @elseif($transaction['type'] == 'payment')
                                    <a href="{{ route('invoices.edit', $transaction['id']) }}" class="btn btn-sm btn-danger" title="تعديل الدفعة">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td></td>
                        <td class="fw-bold">
                            <span class="text-success"> {{ number_format($totalInvoicePieces, 0) }}</span>
                            - <span class="text-warning"> {{ number_format($totalReturnPieces, 0) }}</span>
                            = <span class=""> {{ number_format($totalInvoicePieces - $totalReturnPieces, 0) }}</span>
                        </td>
                        <td class="text-success fw-bold">{{ number_format($totalInvoice, 0) }}</td>
                        <td class="text-warning fw-bold">{{ number_format($totalReturn, 0) }}</td>
                        <td class="text-danger fw-bold">{{ number_format($totalPayment, 0) }}</td>
                        <td class="fw-bold bg-primary text-white">{{ number_format($balance, 0) }}</td>
                        <td></td>
                        <td class="d-print-none"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- التذييل للطباعة -->
    <div class="footer-section d-print-block" style="display: none;">
        <div style="float:left; width:40%;">
            <p>توقيع المسؤول: ________________</p>
        </div>
        <div style="float:right; width:40%; text-align:right;">
            <p>توقيع العميل: ________________</p>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="mt-4">
        <a href="{{ route('clients.index') }}" class="btn btn-secondary d-print-none">
            <i class="fas fa-arrow-left"></i> رجوع إلى العملاء
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/fixedheader/3.2.4/js/dataTables.fixedHeader.min.js"></script>
<script>
    window.addEventListener('beforeprint', function () {
        $('.dataTables_scrollBody').css('overflow', 'visible');
        $('.dataTables_scrollBody').css('height', 'auto');
    });
    window.addEventListener('afterprint', function () {
        $('.dataTables_scrollBody').css('overflow', 'auto');
    });

    $(document).ready(function () {
        var table = $('#transactionsTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "scrollY": "400px",
            "scrollCollapse": true,
            "fixedHeader": {
                header: true,
                footer: true
            },
            "language": {
                "search": "{{ trans('Search') }}:"
            }
        });

        function reloadTable() {
            table.ajax?.reload(null, false);
        }

        setInterval(reloadTable, 30000);
    });
</script>
@endsection
