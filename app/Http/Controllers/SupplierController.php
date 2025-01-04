<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')->select(['id', 'name', 'email', 'created_at', 'status' , 'phone' , 'username']);
            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->join(', ');
                })->addColumn('status', function ($user) {
                    $toggleButton = '<button class="btn btn-sm ' .
                        ($user->status === 'active' ? 'btn-success' : 'btn-light') .
                        ' toggle-status" data-id="' . $user->id . '">' .
                        ($user->status === 'active' ? 'Active' : 'Inactive') .
                        '</button>';
                    return $toggleButton;
                })->addColumn('action', function ($user) {
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
        <button class="btn btn-warning btn-sm edit-user"
                data-id="' . $user->id . '"
                data-name="' . $user->name . '"
                data-email="' . $user->email . '"
                data-phone="' . $user->phone . '"
                data-username="' . $user->username . '"
                data-role="' . optional($user->roles->first())->name . '">
            <i class="fas fa-edit"></i> Edit
        </button>
        <form action="' . route('users.destroy', $user->id) . '" method="POST" class="delete">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
    ';
                })

                ->rawColumns(['status', 'action']) ->make(true);
        }
        $users = User::paginate(10);
        $roles = Role::all();
        $pageTitle = "users";
        return view('users.index', compact(['users', 'roles' , 'pageTitle']));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
