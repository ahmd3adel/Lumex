<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ReturnGoods;
use App\Http\Requests\StoreReturnGoodsRequest;
use App\Http\Requests\UpdateReturnGoodsRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReturnGoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $storeId = Auth::user()->store_id;

            $returns = ReturnGoods::with(['store:id,name', 'client:id,name'])->select('id', 'return_no', 'client_id', 'store_id', 'total', 'discount', 'net_total', 'return_date')
                ->orderBy('created_at' , 'DESC');



            return DataTables::of($returns)
                ->addColumn('client', function ($return) {
                    return '<a href="'.route('clients.show', $return->client->id).'" class="text-primary">
                           ' . e($return->client->name) . '
                        </a>';
                })



                ->addColumn('store', function ($return) {
                    return $return->store->name;
                })
//                ->addColumn('client', function ($return) {
//                    return $return->client->name ?? '';
//                })
                ->addColumn('actions', function ($return) {
                    return '
                <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
                    <a class="btn btn-info btn-sm"
                    href="'.route('returns.show' , $return->id).'"
                            data-id="' . e($return->id) . '"
                            data-name="' . e($return->invoice_no) . '">
                        <i class="fas fa-eye"></i>  ' . trans('view') . '
                    </a>
                    <button class="btn btn-warning btn-sm edit-invoice"
                            data-id="' . e($return->id) . '"
                            data-name="' . e($return->invoice_no) . '">
                        <i class="fas fa-edit"></i>  ' . trans('Edit') . '
                    </button>
                    <form action="' . route('returns.destroy', $return->id) . '" method="POST" class="delete">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> ' . trans('Delete') . '
                        </button>
                    </form>
                </div>
            ';
                })
                ->rawColumns(['actions' , 'client'])
                ->make(true);
        }

        $pageTitle = "Rreturns";
        return view('returns.index', compact('pageTitle'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'add return';
        $clients = Client::all();
        $stores = Store::all();
        $products = Product::all();
        return view('returns.add' , compact(['pageTitle' , 'clients' , 'stores' , 'products']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReturnGoodsRequest $request)
 {

     $validated = $request->validated();

     $return = ReturnGoods::create([
         'return_no'   => 'RET-'.$validated['return_no'],
         'client_id'   =>  $validated['client_id'],
         'store_id'    =>  $validated['store_id'],
         'total'       =>  $validated['total'],
         'discount'    =>  $validated['discount'] ?? 0,
         'net_total'   =>  $validated['total'] - ($request->discount ?? 0),
//         'notes'       =>  $validated['notes'],
         'return_date' =>  $validated['return_date'],
         'created_by'  => Auth::id(),
     ]);



     if (

         count($validated['product_id']) !== count($validated['quantity']) ||
         count($validated['product_id']) !== count($validated['price'])
     ) {
         throw new \Exception('The product data arrays are mismatched.');
     }

     $total_pices = 0;
     // إضافة المنتجات المرتبطة بالفاتورة
     foreach ($validated['product_id'] as $key => $productID) {
         $quantity = $validated['quantity'][$key];
         $price = $validated['price'][$key];
         $subtotal = $quantity * $price;

         $return->products()->create([
             'invoice_id' => $return->id,
             'quantity' => $quantity,
             'product_id' => $productID,
             'unit_price' => $price,
             'subtotal' => $subtotal,
         ]);
         $total_pices += $quantity;
     }


     // تحديث الإجمالي النهائي للفاتورة
     $return->fill([
         'net_total' => $validated['total'],
         'pieces_no' => $total_pices
     ])->save();

     return redirect()->route('returns.index')->with('success', 'Return added successfully.');
 }

    /**
     * Display the specified resource.
     */
    public function show(ReturnGoods $returnGoods)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReturnGoods $returnGoods)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReturnGoodsRequest $request, ReturnGoods $returnGoods)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnGoods $returnGoods)
    {
        //
    }
}
