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

//     dd($request->all());
//     $request->validate([
//         'return_no'   => 'required|unique:return_goods,return_no|max:50',
//         'client_id'   => 'required|exists:clients,id',
//         'store_id'    => 'nullable|exists:stores,id',
//         'return_date' => 'required|date',
//         'total'       => 'required|numeric|min:0',
//         'discount'    => 'nullable|numeric|min:0',
////         'pieces_no'   => 'required|integer|min:1',
//         'product_id.*' => 'required|exists:products,id',
//         'quantity.*'  => 'required|integer|min:1',
//         'price.*'     => 'required|numeric|min:0',
//     ]);

     $return = ReturnGoods::create([
         'return_no'   => 'RET-'.$validated['return_no'],
         'client_id'   =>  $validated['client_id'],
         'store_id'    =>  $validated['store_id'],
         'total'       =>  $validated['total'],
         'discount'    =>  $validated['discount'] ?? 0,
         'net_total'   =>  $validated['total'] - ($request->discount ?? 0),
//         'notes'       =>  $validated['notes'],
         'return_date' =>  $validated['return_date'],
//         'pieces_no'   =>  $validated['pieces_no'],
         'created_by'  => Auth::id(),
     ]);

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
