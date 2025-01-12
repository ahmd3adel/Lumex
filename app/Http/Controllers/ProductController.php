<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with('store')->select(['id', 'name', 'description', 'price', 'quantity' , 'cutter_name' , 'store_id' , 'status'])->orderBy('created_at' , 'desc')->get();

            return DataTables::of($products)
                ->addColumn('name', function ($product) {
                    return $product->name;
                }) ->addColumn('store', function ($product) {
                    return $product->store ? $product->store->name : 'No Store Assigned';
                })->addColumn('description', function ($product) {
                    return $product->description;
                })->addColumn('status', function ($product) {
                    $toggleButton = '<button class="btn btn-sm ' .
                        ($product->status === 'active' ? 'btn-success' : 'btn-light') .
                        ' toggle-status" data-id="' . $product->id . '">' .
                        ($product->status === 'active' ? 'Active' : 'Inactive') .
                        '</button>';
                    return $toggleButton;
                })->addColumn('price', function ($product) {
                    return $product->price;
                })->addColumn('quantity', function ($product) {
                    return $product->quantity;
                })



                ->addColumn('action', function ($product) {
                    return '
                <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
                    <button class="btn btn-info btn-sm view-product"
                            data-id="' . $product->id . '"
                            data-name="' . $product->name . '"
                            data-description="' . $product->description . '"
                            data-price="' . $product->price . '"
                            data-cutter_name="' . $product->cutter_name . '"
                            data-quantity="' . $product->quantity . '">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="btn btn-warning btn-sm edit-product"
                            data-id="' . $product->id . '"
                            data-name="' . $product->name . '"
                            data-description="' . $product->description . '"
                            data-price="' . $product->price . '"
                            data-cutter_name="' . $product->cutter_name . '"
                            data-quantity="' . $product->quantity . '">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <form action="' . route('products.destroy', $product->id) . '" method="POST" class="delete">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>';
                })
                ->rawColumns(['action' , 'status'])
                ->make(true);
        }

        $products = Product::paginate(10);
        $pageTitle = "Products";
        $userRole = Auth::user()->roles->first()->name;
        return view('products.index', compact('products', 'pageTitle' , 'userRole'));
    }
    public function toggleStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'new_status' => $product->status
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $creator = Auth::user();
    $request->validate([
        'name' => 'required|string|min:3|max:255|regex:/^(?!\d+$).*$/|unique:users,name',
        'description' => 'required|min:3',
        'price' => 'required',
        'quantity' => 'required',
        'cutter_name' => 'required|string|min:3',
    ]);
    try {

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'cutter_name' => $request->cutter_name,
            'store_id' => $creator->store_id,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $product,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request , $id)
{
    try {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'description' => 'nullable|string|max:255',
            'price' => [
                'required',
            ],
            'quantity' => 'required',
            'cutter_name' => 'required',
        ]);

        // Find the product by ID
        $product = Product::findOrFail($id);
        // Update product details
        $product->fill([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? $product->description,
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'] ?? $product->quantity,
            'cutter_name' => $validatedData['cutter_name'] ?? $product->cutter_name,
        ]);

        // Save updated product
        $product->save();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'product updated successfully.',
            'data' => $product,
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Handle validation errors
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Handle model not found error
        return response()->json([
            'success' => false,
            'message' => 'product not found.',
        ], 404);
    } catch (\Exception $e) {
        // Log the exception for debugging
        \Log::error('Error updating product: ' . $e->getMessage());

        // Return generic error response
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred.',
            'errors' => 'Please try again later.',
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Product::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');

    }
    public function restore($id)
    {
        try {
            $user = Product::onlyTrashed()->findOrFail($id); // Use findOrFail for better error handling
            $user->restore();

            return redirect()->back()->with('success', 'User restored successfully.');
        } catch (\Exception $e) {
            // Handle errors gracefully
            return redirect()->back()->withErrors(['error' => 'Failed to restore the user.']);
        }
    }
    public function forceDelete($id)
    {
        try {
            $user = Product::onlyTrashed()->findOrFail($id); // Use findOrFail for better error handling
            $user->forceDelete();

            return redirect()->back()->with('success', 'User permanently deleted successfully.');
        } catch (\Exception $e) {
            // Handle errors gracefully
            return redirect()->back()->withErrors(['error' => 'Failed to delete the user permanently.']);
        }
    }
}
