<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPUnit\Exception;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::with(['store:id,name'])
                ->select(['id', 'name', 'company_name', 'website', 'logo', 'phone', 'balance', 'last_login', 'address', 'store_id'])
                ->orderBy('id' , 'DESC');

            return DataTables::of($clients)
                ->addColumn('website', function ($client) {
                    return $client->website ? '<a href="' . $client->website . '" target="_blank">' . $client->website . '</a>' : 'N/A';
                })
                ->addColumn('logo', function ($client) {
                    return $client->logo ? '<img src="' . asset($client->logo) . '" alt="Logo" style="width:50px; height:50px; border-radius:50%;">' : 'No Logo';
                })
                ->addColumn('balance', function ($client) {
                    return number_format($client->balance, 2);
                })
                ->addColumn('address', function ($client) {
                    return $client->address ?? 'N/A';
                })
                ->addColumn('store', function ($client) {
                    return $client->store ? $client->store->name : 'Unassigned';
                })
                ->addColumn('action', function ($client) {
                    return '
                <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
                    <button class="btn btn-info btn-sm view-client"
                            data-id="' . $client->id . '"
                            data-name="' . $client->name . '"
                            data-company_name="' . $client->company_name . '"
                            data-phone="' . $client->phone . '"
                            data-balance="' . $client->balance . '"
                            data-website="' . $client->website . '"
                            data-address="' . $client->address . '">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="btn btn-warning btn-sm edit-client"
                            data-id="' . $client->id . '"
                            data-name="' . $client->name . '"
                            data-company_name="' . $client->company_name . '"
                            data-phone="' . $client->phone . '"
                            data-balance="' . $client->balance . '"
                            data-website="' . $client->website . '"
                            data-address="' . $client->address . '">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <form action="' . route('clients.destroy', $client->id) . '" method="POST" class="delete">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>';
                })
                ->rawColumns(['website', 'logo', 'action'])
                ->make(true);
        }
        $userRole = Auth::user()->roles->pluck('name');
        $clients = Client::paginate(10);
        $pageTitle = "Clients";
        $stores = Store::all();
        return view('clients.index', compact('clients', 'pageTitle' , 'userRole' , 'stores'));
    }


    /**


    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'unique:clients|required|string|min:3|max:255',
            'company_name' => 'required|string|max:255',
            'website' => 'string|max:255',
            'logo' => 'string|max:255',
            'address' => 'string|max:255',
            'phone' => 'required|string',
            'created_by' => 'string',
            'updated_by' => 'string',
            'store_id' => 'string',
        ]);
//        dd($validated);
        try {
            // Create the client
            $client = Client::create($validated);
            if ($client) {
                return response()->json([
                    'success' => true,
                    'message' => 'Client created successfully!',
                    'data' => $client,
                ], 201);
            } else {
                // Return a failure response if client creation failed
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create client.',
                ], 500);
            }
        } catch (\Exception $exception) {
            // Log the exception for debugging
            \Log::error('Error creating client: ', ['error' => $exception->getMessage()]);

            // Return a failure response with the error message
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the client.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|min:3',
                'address' => 'nullable|string|max:255',
                'phone' => [
                    'required',
                    'min:10', // Minimum length for phone numbers
                ],
                'website' => 'nullable|url|max:255',
            ]);

            // Find the client by ID
            $client = Client::findOrFail($id);
//$client->fill([
//    'store_id' => 'ahmed'
//]);
//dd($request->all());
            $client->update($request->all());

            // Update client details
//            $client->fill([
//                'name' => $validatedData['name'],
//                'address' => $validatedData['address'] ?? $client->address,
//                'phone' => $validatedData['phone'],
//                'website' => $validatedData['website'] ?? $client->website,
//            ]);
//
//            // Save updated client
//            $client->save();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully.',
                'data' => $client,
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
                'message' => 'Client not found.',
            ], 404);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error updating client: ' . $e->getMessage());

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
        $user = Client::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');

    }
    public function restore($id)
    {
        try {
            $user = Client::onlyTrashed()->findOrFail($id); // Use findOrFail for better error handling
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
            $user = Client::onlyTrashed()->findOrFail($id); // Use findOrFail for better error handling
            $user->forceDelete();

            return redirect()->back()->with('success', 'User permanently deleted successfully.');
        } catch (\Exception $e) {
            // Handle errors gracefully
            return redirect()->back()->withErrors(['error' => 'Failed to delete the user permanently.']);
        }
    }
}
