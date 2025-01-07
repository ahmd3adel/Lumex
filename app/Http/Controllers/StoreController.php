<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
            $stores = Store::select(['id', 'name', 'location', 'created_at', 'updated_at'])->orderBy('created_at', 'desc');

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


//    public function store(StoreStoreRequest $request)
//    {
//        $request->validate([
//            'name' => 'required|string|min:3|max:255|regex:/^(?!\d+$).*$/|unique:stores,name',
//            'location' => 'required|min:3|max:255',
//
//        ]);
//
//        try {
//            $creator = Auth::user()->name;
//            $store = store::create([
//                'name' => $request->name,
//                'location' => $request->email,
//            ]);
//
////            \Log::info('Store created successfully', [
////                'store_name' => Auth::name,
////                'store_id' => Auth::id(),
////            ]);
//            return response()->json([
//                'success' => true,
//                'message' => 'store created successfully.',
//                'data' => $store,
//            ]);
//        } catch (\Exception $e) {
//
//
//
//
//            return response()->json([
//                'success' => false,
//                'message' => 'Something went wrong.',
//                'error' => $e->getMessage(),
//            ], 500);
//        }
//    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255|regex:/^(?!\d+$).*$/|unique:stores,name',
            'location' => 'required|min:3|max:255',
        ]);


        try {
            $creator = Auth::user()->name;
            $store = Store::create([
                'name' => $request->name,
                'location' => $request->location,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => $store,
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


    public function update(UpdateStoreRequest $request, string $id)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^(?!\d+$).*$/',
                Rule::unique('stores', 'name')->ignore($id),
            ],
            'location' => [
                'required',
                'min:3',
                'max:255',
            ],
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

        // Find the store or return error
        $store = Store::find($id);
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found.',
            ], 404);
        }

        try {
            // Update store details
            $store->update([
                'name' => $request->input('name'),
                'location' => $request->input('location'),
            ]);

            // Log success
            \Log::info('Store updated successfully.', [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'updated_by' => auth()->user()->id ?? 'system',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Store updated successfully.',
                'data' => $store,
            ]);
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error occurred during store update.', [
                'error_message' => $e->getMessage(),
                'store_id' => $store->id ?? 'unknown',
            ]);

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

//    public function trashed(Request $request)
//    {
//        if ($request->ajax()) {
//            $users = User::onlyTrashed()->get(); // جلب المستخدمين المحذوفين فقط
//
//            return DataTables::of($users)
//                ->addColumn('roles', function ($user) {
//                    return $user->roles->pluck('name')->join(', ');
//                })
//                ->addColumn('action', function ($user) {
//                    return '
//<div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
//    <button class="btn btn-info btn-sm view-user"
//            data-id="' . $user->id . '"
//            data-name="' . $user->name . '"
//            data-username="' . $user->username . '"
//            data-phone="' . $user->phone . '"
//            data-role="' . $user->roles->pluck('name')->join(', ') . '"
//            data-joined="' . $user->created_at . '"
//            data-email="' . $user->email . '">
//        <i class="fas fa-eye"></i> View
//    </button>
//<form action="' . route('users.restore', $user->id) . '" method="POST" class="restore-form">
//    ' . csrf_field() . method_field('PUT') . '
//    <button type="submit" class="btn btn-success btn-sm">
//        <i class="fas fa-undo"></i> Restore
//    </button>
//</form>
//<form action="' . route('users.forceDelete', $user->id) . '" method="POST" class="restore-form">
//    ' . csrf_field() . method_field('DELETE') . '
//    <button type="submit" class="btn btn-success btn-sm">
//        <i class="fas fa-undo"></i> Force Delete
//    </button>
//</form>
//
//</div>
//';
//                })
//                ->rawColumns(['status', 'action'])->make(true);
//        }
//
//        $users = User::onlyTrashed()->paginate(10);
//        $roles = Role::all();
//        $pageTitle = "Trashed Users";
//        return view('users.trashedUsers', compact(['users', 'roles', 'pageTitle']));
//    }



    public function destroy(Store $store)
    {
        //
    }
}
