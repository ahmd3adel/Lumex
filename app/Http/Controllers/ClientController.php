<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Invoice;
use App\Models\ReceiptVoucher;
use App\Models\ReturnGoods;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPUnit\Exception;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::select(['id', 'name', 'company_name', 'website', 'phone', 'balance', 'last_login', 'address', 'store_id'])
                ->with(['store:id,name']) // Load store relationship
                ->withCount([
                    'invoices as total_invoices' => function ($query) {
                        $query->select(DB::raw("COALESCE(SUM(net_total), 0)"));
                    },
                    'returns as total_returns' => function ($query) {
                        $query->select(DB::raw("COALESCE(SUM(net_total), 0)"));
                    },
                    'payments as total_payments' => function ($query) {
                        $query->select(DB::raw("COALESCE(SUM(amount), 0)"));
                    }
                ])
                ->get();

            return DataTables::of($clients)
                ->addIndexColumn()
                ->addColumn('balance', function ($client) {
                    // حساب الرصيد = الفواتير - المرتجعات - الدفعات
                    $balance = $client->total_invoices - $client->total_returns - $client->total_payments;
                    return '<span class="badge bg-' . ($balance >= 0 ? 'success' : 'danger') . '">
                            ' . number_format($balance, 0) . '
                        </span>';
                })
                ->addColumn('store', function ($client) {
                    return $client->store ? e($client->store->name) : 'N/A';
                })
                ->addColumn('name', function ($client) {
                    return '<a href="' . route('clients.show', $client->id) . '" class="text-primary">
                           ' . e($client->name) . '
                        </a>';
                })
                ->addColumn('actions', function ($client) {
                    return '
                <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
                    <button class="btn btn-info btn-sm view-client"
                            data-id="' . e($client->id) . '"
                            data-name="' . e($client->name) . '"
                            data-company_name="' . e($client->company_name) . '"
                            data-phone="' . e($client->phone) . '"
                            data-balance="' . e($client->balance) . '"
                            data-website="' . e($client->website) . '"
                            data-address="' . e($client->address) . '">
                        <i class="fas fa-eye"></i> ' . trans('view') . '
                    </button>
                    <button class="btn btn-warning btn-sm edit-client"
                            data-id="' . e($client->id) . '"
                            data-name="' . e($client->name) . '"
                            data-company_name="' . e($client->company_name) . '"
                            data-phone="' . e($client->phone) . '"
                            data-balance="' . e($client->balance) . '"
                            data-website="' . e($client->website) . '"
                            data-address="' . e($client->address) . '">
                        <i class="fas fa-edit"></i>  ' . trans('Edit') . '
                    </button>
                    <form action="' . route('clients.destroy', $client->id) . '" method="POST" class="delete">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>  ' . trans('Delete') . '
                        </button>
                    </form>
                </div>';
                })
                ->rawColumns(['balance', 'actions', 'name', 'store'])
                ->make(true);
        }

        $userRole = Auth::user()->roles->pluck('name');
        $clients = Client::paginate(10);
        $pageTitle = "Clients";
        $stores = Store::all();
        return view('clients.index', compact('clients', 'pageTitle', 'userRole', 'stores'));
    }


    /**
     * Display a listing of the resource.
     */
    /**


    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreClientRequest $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
//            'company_name' => 'required|string|max:255',
            'website' => 'string|max:255',
            'logo' => 'string|max:255',
            'address' => '',
            'phone' => '',
            'created_by' => 'string',
            'updated_by' => 'string',
            'store_id' => 'string',
        ]);
//        dd($validated);
        $validated['company_name'] = Auth::user()->store_id;
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
    public function show($clientId)
    {
        $client = Client::findOrFail($clientId);

        // جلب جميع المعاملات الخاصة بالعميل (فواتير، مرتجعات، دفعات) بترتيب زمني
        $transactions = collect([]);

        // جلب الفواتير (تزيد الرصيد)
        $invoices = Invoice::where('client_id', $clientId)
            ->select('id', 'pieces_no' , 'invoice_no as reference_no', 'total as amount', 'invoice_date as date')
            ->get()
            ->map(function ($invoice) {
                return [
                    'type' => 'invoice',
                    'id' => $invoice->id,
                    'reference_no' => $invoice->reference_no,
                    'amount' => $invoice->amount,
                    'pieces_no' => $invoice->pieces_no,
                    'date' => $invoice->date,
                ];
            });
        // جلب المرتجعات (تخصم من الرصيد)
        $returns = ReturnGoods::where('client_id', $clientId)
            ->select('id','pieces_no' , 'return_no as reference_no', 'net_total as amount', 'return_date as date')
            ->get()
            ->map(function ($return) {
                return [
                    'type' => 'return',
                    'id' => $return->id,
                    'reference_no' => $return->reference_no,
                    'amount' => -$return->amount, // بالسالب لأنه يُخصم من الرصيد
                    'date' => $return->date,
                    'pieces_no' => $return->pieces_no,
                ];
            });

        // جلب الدفعات (تخصم من الرصيد)
        $payments = ReceiptVoucher::where('client_id', $clientId)
            ->select('id', 'voucher_no as reference_no', 'amount', 'receipt_date as date')
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'payment',
                    'id' => $payment->id,
                    'reference_no' => $payment->reference_no,
                    'amount' => -$payment->amount, // بالسالب لأنه يُخصم من الرصيد
                    'date' => $payment->date,
                ];
            });

        // دمج كل العمليات وترتيبها حسب التاريخ
        $transactions = $transactions->merge($invoices)
            ->merge($returns)
            ->merge($payments)
            ->sortBy('date')
            ->values();

        return view('clients.show', compact('client', 'transactions'));
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
    public function getMyClients($store)
    {
        $clients = Client::select('id , name')->where('store_id' , $store)->get();
        $options = [];
        foreach ($clients as $client)
    {
        $options[] = $client->name;
    }

        return response()->json($options);
    }


}
