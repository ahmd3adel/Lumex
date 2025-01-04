<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

//        $stores = Store::select(['id', 'name', 'location', 'created_at', 'updated_at'])->get();
//return response()->json([
//    'stores' => $stores
//]);
        if ($request->ajax()) {
            $stores = Store::select(['id', 'name', 'location', 'created_at', 'updated_at'])->get();

            return DataTables::of($stores)
                ->addColumn('name', function ($store) {
                    return $store->name;
                })
                ->addColumn('location', function ($store) {
                    return $store->location;
                })->addColumn('name', function ($store) {
                    return $store->created_at;
                })->addColumn('name', function ($store) {
                    return $store->updated_at;
                })
                ->addColumn('action', function ($store) {
                    return '
    <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
        <button class="btn btn-info btn-sm view-store"
                data-id="' . $store->id . '"
                data-name="' . $store->name . '"
                data-location="' . $store->location . '"
                data-updated="' . $store->updated_at . '">
            <i class="fas fa-eye"></i> View
        </button>
        <button class="btn btn-warning btn-sm edit-store"
                data-id="' . $store->id . '"
                data-name="' . $store->name . '"
                data-location="' . $store->location . '">
            <i class="fas fa-edit"></i> Edit
        </button>
        <form action="' . route('stores.destroy', $store->id) . '" method="POST" class="delete">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $stores = Store::paginate(10);
        $pageTitle = "Stores";
        return view('stores.index', compact(['stores', 'pageTitle']));
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
    public function store(StoreStoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        //
    }
}
