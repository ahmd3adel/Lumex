<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ReceiptVoucher;
use App\Http\Requests\StoreReceiptVoucherRequest;
use App\Http\Requests\UpdateReceiptVoucherRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\DataTables;

class ReceiptVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        if ($request->ajax()) {
            // Fetch receipts with relationships
            $vouchers = ReceiptVoucher::with(['client:id,name'])
                ->select('id', 'voucher_no', 'client_id', 'amount', 'payment_method', 'receipt_date', 'created_by')
                ->orderBy('created_at', 'DESC')
                ->get(); // ✅ Fix: Execute query

            return DataTables::of($vouchers)
                ->addColumn('client', function ($voucher) {
                    return $voucher->client ? $voucher->client->name : 'N/A';
                })
                ->addColumn('actions', function ($voucher) {
                    return '
                <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
                    <a class="btn btn-info btn-sm"
                    href="'.route('receipts.show', $voucher->id).'">
                        <i class="fas fa-eye"></i> ' . trans('View') . '
                    </a>
                    <button class="btn btn-warning btn-sm edit-receipt"
                            data-id="' . e($voucher->id) . '">
                        <i class="fas fa-edit"></i> ' . trans('Edit') . '
                    </button>
                    <form action="' . route('receipts.destroy', $voucher->id) . '" method="POST" class="delete">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> ' . trans('Delete') . '
                        </button>
                    </form>
                </div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        // Fetch data for normal page load
        $vouchers = ReceiptVoucher::paginate(10);
        $clients= Client::all();
        $pageTitle = "Receipt Vouchers";
        if ($vouchers->isEmpty()) {
            return view('receipts.index', compact('pageTitle' , 'clients'))->with('message', 'No receipts found.');
        }
        return view('receipts.index', compact(['pageTitle' , 'clients', 'vouchers']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReceiptVoucherRequest $request)
    {
        // التحقق من البيانات المدخلة
        $request->validate([
            'voucher_no' => 'nullable|string|max:255|unique:receipt_vouchers,voucher_no,NULL,id,store_id,' . $request->store_id,
            'client_id' => 'required|exists:clients,id',
//            'store_id' => 'required|exists:stores,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|in:cash,bank,credit_card',
            'receipt_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // إنشاء إيصال جديد
        $receipt = $receiptVoucher = ReceiptVoucher::create([
            'voucher_no' => $request->voucher_no,
            'client_id' => $request->client_id,
            'store_id' => Auth::user()->store_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method ?? 'cash',
            'receipt_date' => $request->receipt_date,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);


        return response()->json([
            'success' => true,
            'message' => __('Receipt voucher created successfully!'),
            'data' => $receiptVoucher,
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(ReceiptVoucher $receiptVoucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReceiptVoucher $receiptVoucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReceiptVoucherRequest $request, ReceiptVoucher $receiptVoucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReceiptVoucher $receiptVoucher)
    {
        //
    }
}
