<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
            $users = User::with('roles')->select(['id', 'name', 'email', 'created_at', 'status' , 'phone' , 'username']);
            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->join(', ');
                })->addColumn('status', function ($user) {
                    $toggleButton = '<button class="btn btn-sm ' .
                        ($user->status === 'active' ? 'btn-success' : 'btn-danger') .
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
    public function trashed(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')->select(['id', 'name', 'email', 'created_at' , 'phone' , 'username'])->onlyTrashed();
            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->join(', ');
                })->addColumn('status', function ($user) {
                    $toggleButton = '<button class="btn btn-sm ' .
                        ($user->status === 'active' ? 'btn-success' : 'btn-danger') .
                        ' toggle-status" data-id="' . $user->id . '">' .
                        ($user->status === 'active' ? 'Active' : 'Inactive') .
                        '</button>';
                    return $toggleButton;
                })->addColumn('action', function ($user) {
                    return '
    <div class="d-flex flex-nowrap gap-1 justify-content-start">
        <button class="btn btn-info btn-sm view-user col-md-3"
                data-id="' . $user->id . '"
                data-name="' . $user->name . '"
                data-username="' . $user->username . '"
                data-phone="' . $user->phone . '"
                data-role="' . $user->roles->pluck('name')->join(', ') . '"
                data-joined="' . $user->created_at . '"
                data-email="' . $user->email . '">
            <i class="fas fa-eye"></i> View
        </button>
        <button class="btn btn-warning btn-sm edit-user col-md-3"
                data-id="' . $user->id . '"
                data-name="' . $user->name . '"
                data-email="' . $user->email . '"
                data-phone="' . $user->phone . '"
                data-username="' . $user->username . '"
                data-role="' . optional($user->roles->first())->name . '">
            <i class="fas fa-edit"></i> Edit
        </button>
        <form action="' . route('users.destroy', $user->id) . '" method="POST" style="display:inline-block;" class="delete col-md-3">
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
        $roles = Role::pluck('name')->toArray();

        return view('users/trashedUsers', compact(['users', 'roles']));
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
//        dd($request);

        $request->validate([
            'name' => 'required|string|min:3|max:255|regex:/^(?!\d+$).*$/|unique:users,name',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'phone' => 'required|min:3|max:255',
            'username' => 'required|string|min:3|max:255|unique:users,username',
            'password' => 'required|min:8',
            'status' => 'in:active,inactive'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->role);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => $user,
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        Log::info('Name:', ['name' => $request->input('name')]);
        Log::info('Email:', ['email' => $request->input('email')]);
        Log::info('Phone:', ['phone' => $request->input('phone')]);
        Log::info('Username:', ['username' => $request->input('username')]);
        Log::info('Role:', ['role' => $request->input('role')]);




//            $validatedData = $request->validate([
//                'name' => 'required|string|min:3|max:255|regex:/^(?!\d+$).*$/|unique:users,name,' . $id,
//                'email' => 'required|email|min:3|max:255|unique:users,email,' . $id,
//                'phone' => 'required|min:3|max:255',
//                'username' => 'required|string|min:3|max:255|unique:users,username,' . $id,
//                'password' => 'nullable|min:8',
//            ]);


        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

//        $user->update([
//            'name' => $validatedData['name'],
//            'email' => $validatedData['email'],
//            'phone' => $validatedData['phone'],
//            'username' => $validatedData['username'],
//        ]);

             $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'username' => $request->username,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        if ($request->has('role')) {
            $user->syncRoles($request->role);
        }

        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
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

}
