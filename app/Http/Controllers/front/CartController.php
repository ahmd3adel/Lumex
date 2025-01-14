<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repository\Cart\CartModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $repository;
    public function __construct(CartModelRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = $this->repository->get();
        return view('front.cart' , [
            'cart' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|int|exists:products,id',
            'quantity' => 'nullable|min:1|int'
        ]);
        $product = Product::findOrFail($request->product_id);
        $this->repository->add($product , $request->quantity);
        return redirect()->route('cart.index');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|int|exists:products,id',
            'quantity' => 'nullable|min:1|int'
        ]);

        $product = Product::findOrFail($request->product_id);
        $this->repository->update($product , $product->quantity ?? 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $this->repository->add($product , $product->quantity ?? 1);
    }
}
