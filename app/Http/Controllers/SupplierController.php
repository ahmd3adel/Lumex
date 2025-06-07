<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index(Request $request)
{
    if ($request->ajax()) {
        $suppliers = Supplier::with('store')
            ->select(['id', 'name', 'balance' , 'created_at', 'phone', 'company_name', 'store_id', 'address']);
        
        return DataTables::of($suppliers)
            ->addIndexColumn()
            ->addColumn('store', function ($supplier) {
                return $supplier->store->name ?? '—';
            })
->addColumn('action', function ($supplier) {
    $buttons = '<div class="action-buttons d-flex flex-wrap justify-content-start gap-1">';
    
    // View Button - using route helper
    $buttons .= '<button class="btn btn-info btn-sm view-supplier-btn" 
                data-url="'.route('suppliers.show', $supplier).'"
                title="'.trans('View Supplier').'">
            <i class="fas fa-eye"></i> '.trans('View').'
        </button>';
    
    // Edit Button (hidden for agents)
$buttons .= '<button class="btn btn-warning btn-sm edit-supplier-btn" 
        data-id="' . $supplier->id . '"
        data-name="' . e($supplier->name) . '"
        data-company="' . e($supplier->company_name) . '"
        data-phone="' . e($supplier->phone) . '"
        data-address="' . e($supplier->address) . '"
        data-store="' . e($supplier->store_id) . '"
        title="' . __('Edit Supplier') . '">
    <i class="fas fa-edit"></i> ' . __('Edit') . '
</button>';

    
    // Delete Button (hidden for agents)
    if(!auth()->user()->hasRole('agent')) {
        $buttons .= '<form action="'.route('suppliers.destroy', $supplier).'" method="POST" class="d-inline">
            '.csrf_field().'
            '.method_field('DELETE').'
            <button type="submit" class="btn btn-danger btn-sm delete-btn" 
                    title="'.trans('Delete Supplier').'">
                <i class="fas fa-trash"></i> '.trans('Delete').'
            </button>
        </form>';
    }
    
    $buttons .= '</div>';
    return $buttons;
})
            ->editColumn('created_at', function ($supplier) {
                return $supplier->created_at->format('Y-m-d H:i');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('suppliers.index', [
        'pageTitle' => trans('Suppliers'),
        'stores' => Store::all()
    ]);
}

// public function show(Supplier $supplier)
// {
//     // For API/JSON response
//     if(request()->wantsJson()) {
//         return response()->json([
//             'id' => $supplier->id,
//             'name' => $supplier->name,
//             'company_name' => $supplier->company_name,
//             'phone' => $supplier->phone,
//             'address' => $supplier->address,
//             'store' => $supplier->store ? $supplier->store->name : null,
//             'created_at' => $supplier->created_at->format('Y-m-d H:i'),
//             'updated_at' => $supplier->updated_at->format('Y-m-d H:i')
//         ]);
//     }

//     // For regular web response
//     return view('suppliers.show', compact('supplier'));
// }

// public function show(Supplier $supplier)
// {
//     return response()->json($supplier);
// }

public function show(Supplier $supplier)
{
    return response()->json($supplier->load('store')); // لو تحتاج المتجر أيضًا
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
public function store(StoreSupplierRequest $request)
{
    $validated = $request->validated();
    $validated['created_by'] = auth()->id();
    $validated['updated_by'] = auth()->id();

    try {
        $supplier = Supplier::create($validated);
        
        return response()->json([
            'status' => 'success',
            'title' => 'نجاح',
            'message' => 'تم إنشاء المورد بنجاح',
            'data' => $supplier,
            'timer' => 3000
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'title' => 'خطأ',
            'message' => 'حدث خطأ أثناء إنشاء المورد: ' . $e->getMessage(),
            'timer' => 5000
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    // public function show(Supplier $supplier)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(UpdateSupplierRequest $request, Supplier $supplier)
{
    try {
        DB::beginTransaction();

        // تحديث بيانات المورد
        $supplier->update([
            'name'         => $request->name,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'company_name' => $request->company_name,
            'store_id'     => $request->store_id,
            'updated_by'   => Auth::id(),
        ]);

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => 'تم تحديث بيانات المورد بنجاح.',
            'data'    => $supplier
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'حدث خطأ أثناء تحديث المورد: ' . $e->getMessage(),
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
