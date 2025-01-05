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
        if ($request->ajax()) {
            $stores = Store::select(['id', 'name', 'location', 'created_at', 'updated_at'])->get();

            return DataTables::of($stores)
                ->addColumn('name', function ($store) {
                    return $store->name;
                })
                ->addColumn('location', function ($store) {
                    return $store->location;
                })->addColumn('created_at', function ($store) {
                    return $store->created_at->format('Y-m-d');
                })->addColumn('updated_at', function ($store) {
                    return $store->updated_at->format('Y-m-d');
                })
                ->addColumn('action', function ($store) {
                    return '
    <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
        <button class="btn btn-info btn-sm view-store"
                data-id="' . $store->id . '"
                data-name="' . $store->name . '"
                data-location="' . $store->location . '"
                data-created="' . $store->created_at->format('Y-m-d') . '"
                data-updated="' . $store->updated_at . '">
            <i class="fas fa-eye"></i> View
        </button>
        <button class="btn btn-warning btn-sm edit-store"
                data-id="' . $store->id . '"
                data-name="' . $store->name . '"
                data-location="' . $store->location . '"
                data-created="' . $store->created . '"
                data-updated="' . $store->updated_at . '">
            <i class="fas fa-edit"></i> Edit
        </button>
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

    public function trashed(Request $request)
    {
        if ($request->ajax()) {
            $users = User::onlyTrashed()->get(); // جلب المستخدمين المحذوفين فقط

            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->join(', ');
                })
                ->addColumn('action', function ($user) {
                    return '
<div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
    <button class="btn btn-info btn-sm view-user"
            data-id="' . $user->id . '"
            data-name="' . $user->name . '"
            data-username="' . $user->username . '"
            data-phone="' . $user->phone . '"
            data-role="' . $user->roles->pluck('name')->join(', ') . '"
            data-joined="' . $user->created_at . '"
            data-email="' . $user->email . '">
        <i class="fas fa-eye"></i> View
    </button>
<form action="' . route('users.restore', $user->id) . '" method="POST" class="restore-form">
    ' . csrf_field() . method_field('PUT') . '
    <button type="submit" class="btn btn-success btn-sm">
        <i class="fas fa-undo"></i> Restore
    </button>
</form>
<form action="' . route('users.forceDelete', $user->id) . '" method="POST" class="restore-form">
    ' . csrf_field() . method_field('DELETE') . '
    <button type="submit" class="btn btn-success btn-sm">
        <i class="fas fa-undo"></i> Force Delete
    </button>
</form>

</div>
';
                })
                ->rawColumns(['status', 'action'])->make(true);
        }

        $users = User::onlyTrashed()->paginate(10);
        $roles = Role::all();
        $pageTitle = "Trashed Users";
        return view('users.trashedUsers', compact(['users', 'roles', 'pageTitle']));
    }



    public function destroy(Store $store)
    {
        //
    }
}
