<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Product;
use App\Models\Store;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $storeId = Auth::user()->store_id;

            $invoices = Invoice::with(['client:id,name', 'store:id,name'])
                ->select('id', 'invoice_no', 'client_id', 'store_id', 'total', 'pieces_no', 'net_total', 'invoice_date', 'created_at')
                ->where('store_id', $storeId)
                ->orderBy('invoice_no', 'asc')
                ->get();

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('client', fn($invoice) => $invoice->client ? '<a href="'.route('clients.show', $invoice->client->id).'" class="text-primary">'.e($invoice->client->name).'</a>' : 'N/A')
                ->addColumn('store', fn($invoice) => $invoice->store ? e($invoice->store->name) : 'N/A')
->addColumn('actions', function ($invoice) {
    return '
        <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
            <a class="btn btn-info btn-sm"
               href="'.route('invoices.show', $invoice->id).'"
               data-id="' . e($invoice->id) . '"
               data-name="' . e($invoice->invoice_no) . '">
                <i class="fas fa-eye"></i> ' . trans('view') . '
            </a>
            <a class="btn btn-warning btn-sm edit-invoice"
               href="' . route('invoices.edit' , $invoice->id) . '"
               data-id="' . e($invoice->id) . '"
               data-name="' . e($invoice->invoice_no) . '">
                <i class="fas fa-edit"></i> ' . trans('Edit') . '
            </a>
            <form action="' . route('invoices.destroy', $invoice->id) . '" method="POST" class="delete" style="display:inline;">
                ' . csrf_field() . method_field('DELETE') . '
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد من حذف الفاتورة؟\')">
                    <i class="fas fa-trash"></i> ' . trans('Delete') . '
                </button>
            </form>
        </div>
    ';
})

                ->rawColumns(['actions', 'client'])
                ->make(true);
        }

        return view('invoices.index', ['pageTitle' => 'Invoices']);
    }

    public function office_invoices()
    {
        return view('invoices.office_invoices');
    }

    public function create(Request $request)
    {
        $pageTitle = 'Create Invoice';
        $clients = Client::all();
        $products = Product::all();
        $stores = Store::all();

        $lastInvoice = Invoice::where('store_id', Auth::user()->store_id)->latest()->first();
        $lastInvoiceDate = Invoice::latest()->first();
        $lastProduct = $lastInvoice ? InvoiceDetails::where('invoice_id', $lastInvoice->id)->latest()->first() : null;

        $selectedClientId = $request->query('client_id');

        return view('invoices.add', compact('pageTitle', 'clients', 'products', 'stores', 'lastInvoice', 'lastInvoiceDate', 'lastProduct', 'selectedClientId'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        DB::beginTransaction();

        try {
            $storeId = Auth::user()->hasRole('agent') ? Auth::user()->store_id : $request->store_id;
            $validated = $request->validated();

            if (empty($validated['product_id'])) {
                throw new \Exception('No products selected');
            }

            $invoice = Invoice::create([
                'invoice_no' => $validated['invoice_no'],
                'invoice_date' => $validated['invoice_date'],
                'client_id' => $validated['client_id'],
                'discount' => $validated['discount'] ?? 0,
                'created_by' => Auth::id(),
                'total' => $validated['total'],
                'net_total' => $validated['total'] - ($validated['discount'] ?? 0),
                'store_id' => $storeId,
                'pieces_no' => array_sum($validated['quantity']),
                'status' => 'pending'
            ]);

            $productData = [];
            $totalPieces = 0;

            foreach ($validated['product_id'] as $key => $productId) {
                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception("Product with ID {$productId} not found");
                }

                $quantity = (int) $validated['quantity'][$key];
                $price = (float) $validated['price'][$key];

                if ($quantity <= 0 || $price <= 0) {
                    throw new \Exception('Invalid quantity or price for product: ' . $product->name);
                }

                $productData[] = [
                    'invoice_id' => $invoice->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'subtotal' => $quantity * $price,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $totalPieces += $quantity;
            }

            DB::table('invoice_products')->insert($productData);
            $invoice->update(['pieces_no' => $totalPieces]);

            DB::commit();

            return redirect()->route('invoices.index')->with('success', 'تم إنشاء الفاتورة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Invoice creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'فشل إنشاء الفاتورة: ' . $e->getMessage());
        }
    }

    public function show(Request $request, $invoiceId)
    {
if ($request->ajax()) {
    $inv = Invoice::with(['products.product'])
        ->where('id', $invoiceId)
        ->firstOrFail();

    $products = $inv->products; 

    return DataTables::of($products)
        ->addIndexColumn()
        ->addColumn('product_name', fn($row) => optional($row->product)->name ?? 'غير متوفر')
        ->addColumn('price', fn($row) => number_format($row->unit_price, 2))
        ->addColumn('quantity', fn($row) => $row->quantity)
        ->addColumn('discount', fn($row) => number_format($row->discount ?? 0, 2))
        ->addColumn('subtotal', fn($row) => number_format($row->subtotal, 2))
        ->make(true);
}


        $inv = Invoice::with(['client', 'store'])->findOrFail($invoiceId);
        return view('invoices.show', ['inv' => $inv, 'pageTitle' => 'عرض الفاتورة']);
    }

    public function edit($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $products = Product::all();
        $relatedProducts = $invoice->products;
        $clients = Client::all();

        return view('invoices.edit', [
            'pageTitle' => 'edit invoice',
            'clients' => $clients,
            'products' => $products,
            'invoice' => $invoice,
            'relatedProducts' => $relatedProducts
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $storeId = Auth::user()->hasRole('agent') ? Auth::user()->store_id : $request->store_id;
        $validated = $request->validated();

        try {
            $discount = $validated['discount'] ?? 0;

            $invoice->update([
                'invoice_no' => $validated['invoice_no'],
                'invoice_date' => $validated['invoice_date'],
                'client_id' => $validated['client_id'],
                'discount' => $discount,
                'total' => $validated['total'],
                'net_total' => $validated['total'] - $discount,
                'store_id' => $storeId,
            ]);

            $invoice->products()->delete();

            $totalPieces = 0;
            foreach ($validated['product_id'] as $key => $productId) {
                $quantity = $validated['quantity'][$key];
                $price = $validated['price'][$key];
                $subtotal = $quantity * $price;

                $invoice->products()->create([
                    'quantity' => $quantity,
                    'product_id' => $productId,
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $totalPieces += $quantity;
            }

            $invoice->update(['pieces_no' => $totalPieces]);

            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating invoice: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the invoice.');
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->products()->delete();
            $invoice->delete();
            return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting invoice: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete the invoice.');
        }
    }
}
