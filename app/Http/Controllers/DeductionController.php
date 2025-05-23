<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Http\Requests\StoreDeductionRequest;
use App\Http\Requests\UpdateDeductionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Models\Client;
use App\Models\Product;
use App\Models\Store;



class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(\Illuminate\Http\Request $request)
{
    if ($request->ajax()) {
        $deductions = Deduction::with(['client:id,name', 'store:id,name'])
            ->select('id', 'voucher_no', 'client_id', 'store_id', 'amount', 'receipt_date', 'created_by')
            ->orderBy('created_at', 'DESC')
            ->get();
            
        return DataTables::of($deductions)
            ->addIndexColumn()
            ->addColumn('client', function ($deduction) {
                return $deduction->client ? '<a href="'.route('clients.show', $deduction->client->id).'" class="text-primary">'
                    . e($deduction->client->name) . '</a>' : 'N/A';
            })
            ->addColumn('store', function ($deduction) {
                return $deduction->store ? '<a href="'.route('stores.show', $deduction->store->id).'" class="text-primary">'
                    . e($deduction->store->name) . '</a>' : 'N/A';
            })
            ->addColumn('amount', function ($deduction) {
                return number_format($deduction->amount, 0);
            })
            ->addColumn('receipt_date', function ($deduction) {
                return $deduction->receipt_date ? \Carbon\Carbon::parse($deduction->receipt_date)->format('d/m/Y') : 'N/A';
            })
            ->addColumn('actions', function ($deduction) {
                return '
                <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
                    <a class="btn btn-info btn-sm" href="'.route('deductions.show', $deduction->id).'">
                        <i class="fas fa-eye"></i> ' . trans('View') . '
                    </a>
                    <a class="btn btn-warning btn-sm edit-deduction" data-id="' . e($deduction->id) . '" href="'.route('deductions.edit', $deduction->id).'">
                        <i class="fas fa-edit"></i> ' . trans('Edit') . '
                    </a>
                    <form action="' . route('deductions.destroy', $deduction->id) . '" method="POST" class="delete">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> ' . trans('Delete') . '
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['actions', 'client', 'store'])
            ->make(true);
    }

    $deductions = Deduction::paginate(10);
    $clients = Client::all();
    $pageTitle = "Deductions";
    
    return view('deductions.index', compact('pageTitle', 'clients', 'deductions'))
        ->with('message', $deductions->isEmpty() ? 'No deductions found.' : null);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pageTitle = 'Create Deductions';
        $clients = Client::all();
        $products = Product::all();
        $stores = Store::all();


        return view('deductions.add', compact('pageTitle', 'clients', 'products', 'stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(StoreDeductionRequest $request)
{

    // dd($request->all());
    // إذا كان المستخدم وكيل، نأخذ store_id من المستخدم نفسه
    $storeId = Auth::user()->hasRole('agent') ? Auth::user()->store_id : $request->store_id;

    // التحقق من المدخلات، مع شرط الـ store_id فقط إذا لم يكن وكيل
    $rules = [
        'voucher_no' => 'nullable|string|max:255',
        'client_id'  => 'required|exists:clients,id',
        'amount'     => 'required|numeric|min:0',
        'reason'     => 'nullable|string',
        'date'     => 'nullable',
    ];

    if (!Auth::user()->hasRole('agent')) {
        $rules['store_id'] = 'required|exists:stores,id';
    }

    $validated = $request->validate($rules);

    // توليد رقم سند تلقائي لو لم يرسل المستخدم
    $voucherNo = $validated['voucher_no'] ?? 'V-' . strtoupper(uniqid());

    // بيانات الإدخال
    $data = [
        'voucher_no'   => $voucherNo,
        'client_id'    => $validated['client_id'],
        'store_id'     => $storeId,
        'amount'       => $validated['amount'],
        'notes'        => $validated['reason'] ?? null,
        'receipt_date' => $validated['date'],
        'created_by'   => auth()->id(),
    ];

    try {
        $deduction = Deduction::create($data);

        if ($deduction) {
            return redirect()->back()->with('success', 'تم حفظ الخصم بنجاح');
        } else {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحفظ');
        }
    } catch (\Exception $exception) {
        \Log::error('حدث خطأ أثناء إنشاء الخصم:', ['error' => $exception->getMessage()]);
        return redirect()->back()->with('error', 'حدث خطأ أثناء حفظ الخصم.');
    }
}





    /**
     * Display the specified resource.
     */
    public function show(Deduction $deduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(Deduction $deduction)
{
    $stores = Store::all();
    $clients = Client::all();
$pageTitle = 'edit dedution';
    return view('deductions.edit', compact('deduction', 'stores', 'clients' , 'pageTitle'));
}

public function update(Request $request, Deduction $deduction)
{
    $storeId = Auth::user()->hasRole('agent') ? Auth::user()->store_id : $request->store_id;

    $rules = [
        'voucher_no' => 'nullable|string|max:255',
        'client_id'  => 'required|exists:clients,id',
        'amount'     => 'required|numeric|min:0',
        'reason'     => 'nullable|string',
    ];

    if (!Auth::user()->hasRole('agent')) {
        $rules['store_id'] = 'required|exists:stores,id';
    }

    $validated = $request->validate($rules);

    $deduction->update([
        'voucher_no'   => $validated['voucher_no'] ?? '',
        'client_id'    => $validated['client_id'],
        'store_id'     => $storeId,
        'amount'       => $validated['amount'],
        'notes'        => $validated['reason'] ?? null,
    ]);

    return redirect()->route('deductions.index')->with('success', 'تم تحديث الخصم بنجاح');
}


    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateDeductionRequest $request, Deduction $deduction)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deduction $deduction)
    {
        //
    }
}
