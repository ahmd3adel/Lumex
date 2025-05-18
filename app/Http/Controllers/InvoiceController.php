<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\InvoiceDetails;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

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
                    <a class="btn btn-warning btn-sm edit-invoice"
                    href="' . route('invoices.edit' , $invoice->id) . '"
                            data-id="' . e($invoice->id) . '"
                            data-name="' . e($invoice->invoice_no) . '">
                        <i class="fas fa-edit"></i> ' . trans('Edit') . '
                    </a>
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
        // الحصول على جميع البيانات اللازمة لعرضها في النموذج
        $clients = Client::get();
        $products = Product::get();
        $stores = Store::get();

        // الحصول على آخر رقم فاتورة مضاف
        $lastInvoice = Invoice::where('store_id', Auth::user()->store_id)
        ->orderByRaw('created_at DESC')
        ->first();



        $lastInvoiceDate = Invoice::orderBy('created_at', 'desc')->first();

        $lastProduct = $lastInvoice ? InvoiceDetails::where('invoice_id', $lastInvoice->id)->latest()->first() : null;

        return view('invoices.add', compact(['pageTitle', 'clients', 'products', 'stores', 'lastInvoice', 'lastInvoiceDate' , 'lastProduct']));
    }



public function store(StoreInvoiceRequest $request)
{
    DB::beginTransaction();
    
    try {
        $storeId = Auth::user()->hasRole('agent') ? Auth::user()->store_id : $request->store_id;
        $validated = $request->validated();
        
        // تحقق إضافي من المنتجات
        if (empty($validated['product_id'])) {
            throw new \Exception('No products selected');
        }

        // إنشاء الفاتورة
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
            'status' => 'pending' // إضافة حالة افتراضية
        ]);

        // إضافة المنتجات مع التحقق
        $productData = [];
        $totalPieces = 0;
        
        foreach ($validated['product_id'] as $key => $productId) {
            // التحقق من وجود المنتج
            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception("Product with ID {$productId} not found");
            }

            $quantity = (int)$validated['quantity'][$key];
            $price = (float)$validated['price'][$key];
            
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

        // إدخال جميع المنتجات دفعة واحدة
        DB::table('invoice_products')->insert($productData);

        // تحديث إجمالي القطع
        $invoice->update(['pieces_no' => $totalPieces]);

        DB::commit();
        
        return redirect()->route('invoices.index')
               ->with('success', 'تم إنشاء الفاتورة بنجاح');
               
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Invoice creation failed: ' . $e->getMessage());
        
        return redirect()->back()
               ->withInput()
               ->with('error', 'فشل إنشاء الفاتورة: ' . $e->getMessage());
    }
}






    /**
     * Display the specified resource.
     */
//    public function show($invoice)
//    {
//        $inv = Invoice::findOrFail($invoice);
//        $pageTitle = 'Show invoice';
//        return view('invoices.show' , compact(['pageTitle' , 'inv']));
//    }

    public function show(Request $request, $invoiceId)
    {
        if ($request->ajax()) {
            $inv= Invoice::with(['products.product'])
                ->where('id', $invoiceId)
                ->where('store_id', Auth::user()->store_id)
                ->firstOrFail();

            $products = $inv->products;

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('product_name', fn($row) => optional($row->product)->name ?? 'غير متوفر')
                ->addColumn('price', fn($row) => number_format($row->unit_price, 2))
                ->addColumn('quantity', fn($row) => $row->quantity)
                    ->addColumn('discount', fn($row) => number_format($row->discount, 2)) // ✅ أضف هذا السطر
                ->addColumn('subtotal', fn($row) => number_format($row->subtotal, 2))
                ->make(true);
        }

        // لو الطلب مش AJAX، نعرض الـ View العادية
        $inv = Invoice::with(['client', 'store'])->findOrFail($invoiceId);
        $pageTitle = 'عرض الفاتورة';

        return view('invoices.show', compact('inv', 'pageTitle'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($invoice)
    {
        $invoice = Invoice::findOrFail($invoice);
        
        // $inv= Invoice::with(['products.product'])
        // ->where('id', $invoiceId)
        // ->where('store_id', Auth::user()->store_id)
        // ->firstOrFail();
        $products = Product::all();
        $relatedProducts = $invoice->products;
        // dd($relatedProducts);
        $pageTitle = 'edit invoice';
        $clients = Client::get();
        $products = Product::get();
        return view('invoices.edit' , compact(['pageTitle' , 'clients' , 'products' , 'invoice' , 'relatedProducts']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $storeId = Auth::user()->hasRole('agent') ? Auth::user()->store_id : $request->store_id;
        $validated = $request->validated();
        try {
            $discount = $validated['discount'] ?? 0;
    
            // تحديث بيانات الفاتورة الأساسية
            $invoice->update([
                'invoice_no' => $validated['invoice_no'],
                'invoice_date' => $validated['invoice_date'],
                'client_id' => $validated['client_id'],
                'discount' => $discount,
                'total' => $validated['total'],
                'net_total' => $validated['total'] - $discount,
                'store_id' => $storeId,
            ]);
    
            // حذف المنتجات المرتبطة القديمة
            $invoice->products()->delete();
    
            // إعادة إدخال المنتجات من الطلب الجديد
            $total_pieces = 0;
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
                $total_pieces += $quantity;
            }
    
            $invoice->update(['pieces_no' => $total_pieces]);
    
            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating invoice: ', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while updating the invoice. Please try again.');
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->products()->delete(); // حذف المنتجات المرتبطة أولاً
            $invoice->delete(); // حذف الفاتورة نفسها
    
            return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting invoice: ', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete the invoice.');
        }
    }
    
}
