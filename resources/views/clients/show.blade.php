@extends('layouts.index')

@section('title', 'Store Details')

@section('breadcramp')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
{{--                    <div class="col-sm-6">--}}
{{--                        <h4 class="">{{ trans('Store Details') }} <span class="" style="color: #589eeb">{{ $client->name }}</span> </h4>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-6">--}}
{{--                        @parent--}}
{{--                        <ol class="breadcrumb float-sm-right">--}}
{{--                            <li class="breadcrumb-item"><a href="{{ route('stores.index') }}">{{ trans('Stores') }}</a></li>--}}
{{--                            <li class="breadcrumb-item active">{{ trans('Store Details') }}</li>--}}
{{--                        </ol>--}}
{{--                    </div>--}}

                                            <h4 class="m-auto">{{ trans('Store Details') }} <span class="" style="color: #589eeb">{{ $client->name }}</span> </h4>


                </div>
            </div>
        </div>
        @endsection

        @section('content')
            <div class="container">
                <div class="card p-4">
{{--                    <div class="card-header bg-primary text-white text-right">--}}
{{--                        <h3 class="card-title" style="float: right">--}}
{{--                            <i class="fas fa-user"></i> {{ $client->name }}--}}
{{--                        </h3>--}}
{{--                    </div>--}}
                    <div class="card-body">
                        <table id="transactionsTable" class="table table-bordered table-striped text-center align-middle">
                            <thead class="table-dark">
                            <tr>
                                <th>{{ trans('Reference No') }}</th>
                                <th>{{ trans('pieces') }}</th>
                                <th>{{ trans('Invoice') }}</th>
                                <th>{{ trans('Return') }}</th>
                                <th>{{ trans('Return Pieces') }}</th> <!-- إضافة عمود جديد لعدد قطع المرتجع -->
                                <th>{{ trans('Payment') }}</th>
                                <th class="bg-primary text-white">{{ trans('Balance') }}</th>
                                <th>{{ trans('Date') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $balance = 0;
                                $totalInvoice = 0;
                                $totalReturn = 0;
                                $totalPayment = 0;
                                $totalPieces_no = 0;
                                $totalReturnPieces_no = 0;
                            @endphp
                            @foreach($transactions as $transaction)
                                @php
                                    $balance += $transaction['amount'];
                                    if ($transaction['type'] == 'invoice') {
                                        $totalInvoice += $transaction['amount'];
                                        $totalPieces_no += $transaction['pieces_no'];
                                    } elseif ($transaction['type'] == 'return') {
                                        $totalReturn += $transaction['amount'];
                                        $totalReturnPieces_no += $transaction['pieces_no'];
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
                                    <td class="text-success fw-bold">{{ $transaction['type'] == 'invoice' ? number_format($transaction['pieces_no'], 0) : '-' }}</td>
                                    <td class="text-success fw-bold">{{ $transaction['type'] == 'invoice' ? number_format($transaction['amount'], 0) : '-' }}</td>
                                    <td class="text-warning fw-bold">{{ $transaction['type'] == 'return' ? number_format($transaction['amount'], 0) : '-' }}</td>
                                    <td class="text-warning fw-bold">{{ $transaction['type'] == 'return' ? number_format($transaction['pieces_no'], 0) : '-' }}</td> <!-- عرض عدد قطع المرتجع -->
                                    <td class="text-danger fw-bold">{{ $transaction['type'] == 'payment' ? number_format($transaction['amount'], 0) : '-' }}</td>
                                    <td class="fw-bold bg-light">{{ number_format($balance, 0) }}</td>
                                    <td><span class="badge bg-secondary">{{ \Carbon\Carbon::parse($transaction['date'])->format('d-m-Y') }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                            <tr>
                                <td class="text-success fw-bold"></td>
                                <td class="fw-bold">{{ number_format($totalPieces_no, 0) }}

                                </td>
                                <td class="text-success fw-bold">{{ number_format($totalInvoice, 0) }}</td>
                                <td class="text-warning fw-bold">{{ number_format($totalReturn, 0) }}</td>
                                <td class="text-warning fw-bold">{{ number_format($totalReturnPieces_no, 0) }}</td> <!-- إجمالي عدد قطع المرتجع -->
                                <td class="text-danger fw-bold">{{ number_format($totalPayment, 0) }}</td>
                                <td class="fw-bold bg-primary text-white">{{ number_format($balance, 0) }}</td>
                                <td class="text-success fw-bold"></td>
                            </tr>
                            </tfoot>
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

        @section('scripts')
            <script src="https://cdn.datatables.net/fixedheader/3.2.4/js/dataTables.fixedHeader.min.js"></script>
            <script>
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

                    // تحديث الجدول دون إعادة تحميل الصفحة
                    function reloadTable() {
                        table.ajax.reload(null, false); // تحميل البيانات الجديدة بدون تحديث الصفحة
                    }

                    // تحديث الجدول تلقائيًا كل 30 ثانية
                    setInterval(reloadTable, 30000);
                });

            </script>
@endsection
