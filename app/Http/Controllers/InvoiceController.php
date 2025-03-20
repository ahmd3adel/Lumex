<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $storeId = Auth::user()->store_id;

            $invoices = Invoice::with(['client:id,name', 'store:id,name'])
                ->select('id', 'invoice_no', 'client_id', 'store_id', 'total', 'pieces_no', 'net_total', 'invoice_date', 'created_at')
                ->where('store_id', $storeId)
                ->orderBy('invoice_no' , 'asc')
                ->get();




            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('client', function ($invoice) {
                    return $invoice->client ? '<a href="'.route('clients.show', $invoice->client->id).'" class="text-primary">'
                        . e($invoice->client->name) . '</a>' : 'N/A';
                })
                ->addColumn('store', function ($invoice) {
                    return $invoice->store ? e($invoice->store->name) : 'N/A';
                })
                ->addColumn('actions', function ($invoice) {
                    return '
                <div class="action-buttons d-flex flex-wrap justify-content-start gap-1">
                    <a class="btn btn-info btn-sm"
                    href="'.route('invoices.show', $invoice->id).'"
                            data-id="' . e($invoice->id) . '"
                            data-name="' . e($invoice->invoice_no) . '">
                        <i class="fas fa-eye"></i> ' . trans('view') . '
                    </a>
                    <button class="btn btn-warning btn-sm edit-invoice"
                            data-id="' . e($invoice->id) . '"
                            data-name="' . e($invoice->invoice_no) . '">
                        <i class="fas fa-edit"></i> ' . trans('Edit') . '
                    </button>
                    <form action="' . route('invoices.destroy', $invoice->id) . '" method="POST" class="delete">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> ' . trans('Delete') . '
                        </button>
                    </form>
                </div>
            ';
                })
                ->rawColumns(['actions', 'client'])
                ->make(true);
        }

        $pageTitle = "Invoices";
        return view('invoices.index', compact('pageTitle'));
    }




    public function office_invoices()
{
    return view('invoices.office_invoices');
}


    public function create()
    {
        $pageTitle = 'Create Invoice';
        $clients = Client::get();
        $products = Product::get();
        $stores = Store::get();
        return view('invoices.add' , compact(['pageTitle' , 'clients' , 'products' , 'stores']));
    }

    public function store(StoreInvoiceRequest $request)
    {
        // التحقق من دور المستخدم وتحديد store_id
        $storeId = Auth::user()->hasRole('agent') ? Auth::user()->store_id : $request->store_id;

        // التحقق من صحة البيانات المُرسلة
        $validated = $request->validated();

        try {
            // استخراج الخصم، إذا لم يُرسل يتم تعيينه إلى 0
            $discount = $validated['discount'] ?? 0;

            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'invoice_no' => $validated['invoice_no'],
                'invoice_date' => $validated['invoice_date'],
                'client_id' => $validated['client_id'],
                'discount' => $discount,
                'created_by' => Auth::id(),
                'total' => $validated['total'],
                'net_total' => 0, // سيتم تحديثه لاحقًا
                'store_id' => $storeId
            ]);

            // التحقق من تطابق أطوال المصفوفات
            if (
                count($validated['product_id']) !== count($validated['quantity']) ||
                count($validated['product_id']) !== count($validated['price'])
            ) {
                throw new \Exception('The product data arrays are mismatched.');
            }
            $total_pices = 0;
            // إضافة المنتجات المرتبطة بالفاتورة
            foreach ($validated['product_id'] as $key => $productID) {
                $quantity = $validated['quantity'][$key];
                $price = $validated['price'][$key];
                $subtotal = $quantity * $price;

                $invoice->products()->create([
                    'invoice_id' => $invoice->id,
                    'quantity' => $quantity,
                    'product_id' => $productID,
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                ]);
                $total_pices += $quantity;
            }

            // تحديث الإجمالي النهائي للفاتورة
            $invoice->fill([
                'net_total' => $validated['total'] - $discount,
                'pieces_no' => $total_pices
            ])->save();

            // نجاح العملية
            return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            // تسجيل الخطأ
            \Log::error('Error creating invoice: ', ['error' => $e->getMessage()]);

            // فشل العملية
            return redirect()->back()->with('error', 'An error occurred while creating the invoice. Please try again.');
        }
    }






    /**
     * Display the specified resource.
     */
    public function show($invoice)
    {
        $inv = Invoice::findOrFail($invoice);
        $pageTitle = 'Show invoice';
        return view('invoices.show' , compact(['pageTitle' , 'inv']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
