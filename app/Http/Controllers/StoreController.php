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
            $stores = Store::with('users:id,name')->select(['id', 'name', 'location', 'created_at', 'updated_at'])->withCount([
                'users' => function ($query) {
                    $query->where('status' , '=' , 'active');
                }
            ])->orderBy('created_at', 'desc');

            return DataTables::of($stores)
                ->addColumn('name', function ($store) {
                    return '<a href="'.route('stores.show', $store->id).'" class="text-primary">
                           ' . e($store->name) . '
                        </a>';
                })
                ->addColumn('users', function ($store) {
                    return $store->location;
                })
                ->addColumn('users', function ($store) {
                    return '<a href="' . route("stores.users", ['id' => $store->id]) . '">' . $store->users_count . '</a>';
                })

                ->addColumn('created_at', function ($store) {
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
            <i class="fas fa-eye"></i> ' . trans('View') . '
        </button>
        <button class="btn btn-warning btn-sm edit-store"
                data-id="' . $store->id . '"
                data-name="' . $store->name . '"
                data-location="' . $store->location . '"
                data-created="' . $store->created . '"
                data-updated="' . $store->updated_at . '">
            <i class="fas fa-edit"></i> ' . trans('Edit') . '
        </button>
    </div>
    ';
                })
                ->rawColumns(['action' , 'users' , 'name'])
                ->make(true);
        }

        $stores = Store::paginate(10);
        $pageTitle = "Stores";
        return view('stores.index', compact(['stores', 'pageTitle']));
    }

    public function show($id)
    {
        $store = Store::find($id);
        return view('stores.show' , compact('store'));
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */



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
//public function users($id)
//{
//    $store = Store::findOrFail($id); // التحقق من وجود المتجر
//      $users = $store->users;
//    return DataTables::of($users)
//        ->addColumn('name', function ($user) {
//            return $user->name; // اسم المستخدم
//        })
//        ->addColumn('email', function ($user) {
//            return $user->email; // بريد المستخدم
//        })
//        ->addColumn('username', function ($user) {
//            return $user->username; // اسم المستخدم
//        })
//        ->addColumn('phone', function ($user) {
//            return $user->phone; // هاتف المستخدم
//        })
//        ->addColumn('roles', function ($user) {
//            return $user->roles->pluck('name')->join(', '); // الأدوار المرتبطة بالمستخدم
//        })
//        ->addColumn('status', function ($user) {
//            $statusClass = $user->status === 'active' ? 'btn-success' : 'btn-light';
//            $statusText = $user->status === 'active' ? 'Active' : 'Inactive';
//            return '<button class="btn btn-sm ' . $statusClass . ' toggle-status" data-id="' . $user->id . '">' . $statusText . '</button>';
//        })
//        ->addColumn('action', function ($user) {
//            return '
//                    <a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-warning">Edit</a>
//                    <form action="' . route('users.destroy', $user->id) . '" method="POST" style="display:inline-block;">
//                        ' . csrf_field() . method_field('DELETE') . '
//                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
//                    </form>
//                ';
//        })
//        ->rawColumns(['status', 'action']) // السماح بعرض HTML في الأعمدة
//        ->make(true);
//}

    /**
     * Show the form for editing the specified resource.
     */
    public function users($id)
    {
        $store = Store::findOrFail($id);

        // جلب المستخدمين المرتبطين بالمتجر مع الأدوار
        $users = $store->users()->with('roles')->get();

        if (request()->ajax()) {
            return DataTables::of($users)
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('email', function ($user) {
                    return $user->email ?? 'N/A';
                })
                ->addColumn('username', function ($user) {
                    return $user->username;
                })
                ->addColumn('phone', function ($user) {
                    return $user->phone;
                })
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->join(', ');
                })
                ->addColumn('status', function ($user) {
                    $toggleButton = '<button class="btn btn-sm ' .
                        ($user->status === 'active' ? 'btn-success' : 'btn-light') .
                        ' toggle-status" data-id="' . $user->id . '">' .
                        ($user->status === 'active' ? 'Active' : 'Inactive') .
                        '</button>';
                    return $toggleButton;
                })
                ->addColumn('action', function ($user) {
                    return '
                    <a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-warning">Edit</a>
                    <form action="' . route('users.destroy', $user->id) . '" method="POST" style="display:inline-block;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $pageTitle = 'Users for Store: ' . $store->name;
        return view('stores.relatedUsers', compact('store', 'pageTitle', 'id'));
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



    public function destroy(Store $store)
    {
        //
    }
}
