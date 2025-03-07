<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPUnit\Exception;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
if ($request->ajax()) {
    $users = User::with([
        'roles:name', // Load only the name field of roles
        'store:id,name' // Load only the id and name fields of store
    ])
        ->select([
            'id', 'name', 'email', 'created_at', 'status', 'phone', 'username', 'store_id', 'created_by', 'updated_by'
        ])
        ->where('id' , '!=' , Auth::id())
        ->whereHas('roles', function ($query) {
            $query->where('name', '!=', 'super admin');
        })
        ->orderBy('created_at', 'desc');


    return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->join(', ');
                }) ->addColumn('store', function ($user) {
//                    return $user->store ? $user->store->name : 'N/A';
                    return $user->store
                        ? '<a href="#" class="open-store-modal" data-id="' . $user->store->id . '" data-location = "' . $user->store->location . '">' . $user->store->name . '</a>'
                        : '';
                })->addColumn('status', function ($user) {
                    $toggleButton = '<button class="btn btn-sm ' .
                        ($user->status === 'active' ? 'btn-success' : 'btn-light') .
                        ' toggle-status" data-id="' . $user->id . '">' .
                        ($user->status === 'active' ? 'Active' : 'Inactive') .
                        '</button>';
                    return $toggleButton;
                })->addColumn('created by',function ($user){
                    return $user->createdBy->name ?? "";
                })->addColumn('updated by',function ($user){
                    return $user->updatedBy->name ?? "";
                })

        ->addColumn('action', function ($user) {
            return '
    <div class="action-buttons d-flex align-items-center flex-wrap gap-1">
        <!-- زر العرض -->
<button type="button" class="btn btn-info btn-sm view-user"
        data-id="' . htmlspecialchars($user->id) . '"
        data-name="' . htmlspecialchars($user->name) . '"
        data-username="' . htmlspecialchars($user->username) . '"
        data-phone="' . htmlspecialchars($user->phone) . '"
        data-role="' . htmlspecialchars($user->roles->pluck('name')->join(', ')) . '"
        data-joined="' . $user->created_at->format('Y-m-d H:i:s') . '"
        data-store_id="' . htmlspecialchars($user->store_id) . '"
        data-email="' . htmlspecialchars($user->email) . '">
    <i class="fas fa-eye"></i> ' . trans('view') . '
</button>


        <!-- زر التعديل -->
        <button type="button" class="btn btn-warning btn-sm edit-user"
                data-id="' . htmlspecialchars($user->id) . '"
                data-name="' . htmlspecialchars($user->name) . '"
                data-email="' . htmlspecialchars($user->email) . '"
                data-phone="' . htmlspecialchars($user->phone) . '"
                data-username="' . htmlspecialchars($user->username) . '"
                data-role="' . htmlspecialchars(optional($user->roles->first())->name) . '">
            <i class="fas fa-edit"></i> ' . trans('Edit') . '
        </button>

        <!-- زر الحذف -->
        <form action="' . route('users.destroy', $user->id) . '" method="POST" class="delete d-inline-block">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> ' . trans('Delete') . '
            </button>
        </form>
    </div>
    ';
        })


        ->rawColumns(['status', 'action' , 'store']) ->make(true);
        }
        $users = User::paginate(10);
        $roles = Role::all();
        $pageTitle = "users";
        $stores = Store::all();
        return view('users.index', compact(['users', 'roles' , 'pageTitle' , 'stores']));
    }

    public function toggleStatus(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'new_status' => $user->status
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Request in middleware before merge: ', $request->all());

        if (Auth::user()->hasRole('agent'))
        {
            $storeId = Auth::user()->store_id;
        }
        else
        {
            $storeId = $request->store_id;
        }

        $request->validate([
            'name' => 'required|string|min:3|max:255|regex:/^(?!\d+$).*$/|unique:users,name',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'phone' => 'required|min:3|max:255',
            'username' => 'required|string|min:3|max:255|unique:users,username',
            'password' => 'required|min:8',
            'store_id' => 'required|exists:stores,id',
            'role' => 'required|exists:roles,name',
            'status' => 'in:active,inactive',
            'user_id' => 'exists:users,id'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'username' => $request->username,
                'store_id' => $request->store,
                'password' => Hash::make($request->password),

            ]);

            if (Role::where('name', $request->role)->exists()) {
                $user->assignRole($request->role);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid role provided.',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in user creation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^(?!\d+$).*$/',
                Rule::unique('users', 'name')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                'min:3',
                'max:255',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'phone' => 'required|min:3|max:255',
            'username' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('users', 'username')->ignore($id),
            ],
            'password' => 'nullable|string|min:8',
            'role' => 'nullable|string|exists:roles,name',
            'status' => 'in:active,inactive',
            'user_id' => 'exists:users,id',
        ];

        // Validate request manually
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find the user or return error
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        try {
            // Update user details
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'username' => $request->input('username'),
            ]);

//            $user->update($request->all());
            // Update password if provided
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->input('password'))]);
            }

            // Sync roles if provided
            if ($request->has('role')) {
                $user->syncRoles($request->input('role'));
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error occurred during update:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');

    }
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
    <button type="submit" class="btn btn-danger btn-sm">
        <i class="fas fa-trash"></i> Force Delete
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

    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id); // Use findOrFail for better error handling
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
            $user = User::onlyTrashed()->findOrFail($id); // Use findOrFail for better error handling
            $user->forceDelete();

            return redirect()->back()->with('success', 'User permanently deleted successfully.');
        } catch (\Exception $e) {
            // Handle errors gracefully
            return redirect()->back()->withErrors(['error' => 'Failed to delete the user permanently.']);
        }
    }

    public function getStore($id)
    {
        $store = Store::find($id);

        if ($store) {
            return response()->json([
                'success' => true,
                'data' => $store,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Store not found',
        ], 404);
    }


}
