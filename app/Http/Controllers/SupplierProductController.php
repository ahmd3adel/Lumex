<?php

namespace App\Http\Controllers;

use App\Models\SupplierProduct;
use App\Http\Requests\StoreSupplierProductRequest;
use App\Http\Requests\UpdateSupplierProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // تأكد أنك مستورد Log في أعلى الملف


class SupplierProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    if ($request->ajax()) {
        $products = SupplierProduct::with('store')
            ->select(['id', 'name', 'description', 'store_id', 'status'])
            ->orderBy('created_at', 'desc');

        return DataTables::eloquent($products)
            ->addIndexColumn()
            ->addColumn('store', function ($product) {
                return $product->store ? $product->store->name : 'No Store Assigned';
            })
            ->addColumn('status', function ($product) {
                $status = $product->status === 'active' ? 'Active' : 'Inactive';
                $class = $product->status === 'active' ? 'btn-success' : 'btn-light';
                return '<button class="btn btn-sm '.$class.' toggle-status" data-id="'.$product->id.'">'.$status.'</button>';
            })
            ->addColumn('action', function ($product) {
                return '
                <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
                    <button class="btn btn-info btn-sm view-product"
                            data-id="'.$product->id.'"
                            data-name="'.$product->name.'"
                            data-description="'.$product->description.'">
                        <i class="fas fa-eye"></i> '.trans('View').'
                    </button>
                    <button class="btn btn-warning btn-sm edit-product"
                            data-id="'.$product->id.'"
                            data-name="'.$product->name.'"
                            data-description="'.$product->description.'">
                        <i class="fas fa-edit"></i> '.trans('Edit').'
                    </button>
                    <form action="'.route('products.destroy', $product->id).'" method="POST" class="delete">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> '.trans('Delete').'
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    $pageTitle = "Products";
    $userRole = Auth::user()->roles->first()->name ?? "";
    $stores = Store::all();
    return view('supplierProducts.index', compact('pageTitle', 'userRole', 'stores'));
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

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|min:3|max:255|regex:/^(?!\d+$).*$/|unique:supplier_products,name',
        'store_id' => 'required|exists:stores,id',
    ]);

    try {
        // تسجيل البيانات المرسلة في السجل
        \Log::info('Creating product with data:', $request->all());

        $product = SupplierProduct::create([
            'name' => $request->name,
            'description' => $request->description,
            'store_id' => $request->store_id,
            'created_by' => auth()->id(), // إذا كنت تريد تسجيل من أنشأ المنتج
        ]);

        \Log::info('Product created successfully:', $product->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $product,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in storing product: ' . $e->getMessage());
        \Log::error('Error trace:', $e->getTrace());
        
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Error: ' . $e->getMessage(),
        ], 500);
    }
}




    /**
     * Display the specified resource.
     */
    public function show(SupplierProduct $supplierProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierProduct $supplierProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierProductRequest $request, SupplierProduct $supplierProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierProduct $supplierProduct)
    {
        //
    }
}
