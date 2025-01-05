<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;
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
            $clients = Client::select(['id', 'name', 'company_name', 'website', 'logo', 'phone', 'balance', 'last_login', 'address', 'user_id'])->get();

            return DataTables::of($clients)
                ->addColumn('name', function ($client) {
                    return $client->name;
                })
                ->addColumn('company_name', function ($client) {
                    return $client->company_name;
                })
                ->addColumn('website', function ($client) {
                    return $client->website ? '<a href="' . $client->website . '" target="_blank">' . $client->website . '</a>' : 'N/A';
                })
                ->addColumn('logo', function ($client) {
                    return $client->logo ? '<img src="' . asset($client->logo) . '" alt="Logo" style="width:50px; height:50px; border-radius:50%;">' : 'No Logo';
                })
                ->addColumn('phone', function ($client) {
                    return $client->phone;
                })
                ->addColumn('balance', function ($client) {
                    return number_format($client->balance, 2);
                })
                ->addColumn('address', function ($client) {
                    return $client->address ?? 'N/A';
                })
                ->addColumn('user_id', function ($client) {
                    return $client->user ? $client->user->name : 'Unassigned';
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
                            data-address="' . $client->address . '">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="btn btn-warning btn-sm edit-client"
                            data-id="' . $client->id . '"
                            data-name="' . $client->name . '"
                            data-company_name="' . $client->company_name . '"
                            data-phone="' . $client->phone . '"
                            data-balance="' . $client->balance . '"
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

        $clients = Client::paginate(10);
        $pageTitle = "Clients";
        return view('clients.index', compact('clients', 'pageTitle'));
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
    public function store(StoreClientRequest $request)
    {
        //
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
    public function update(UpdateClientRequest $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
