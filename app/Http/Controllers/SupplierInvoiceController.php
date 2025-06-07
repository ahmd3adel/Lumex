<?php

namespace App\Http\Controllers;

use App\Models\SupplierInvoice;
use App\Http\Requests\StoreSupplierInvoiceRequest;
use App\Http\Requests\UpdateSupplierInvoiceRequest;
use Illuminate\Http\Request; // هذا هو الاستيراد الصحيح
use App\Models\Supplier;
use App\Models\Store;
use App\Models\SupplierInvoiceDetail;
use DataTables;
use App\Models\SupplierInvoiceItem;
use App\Models\SupplierProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SupplierInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index(Request $request)
{
    if ($request->ajax()) {
        $invoices = SupplierInvoice::with(['supplier', 'store']);

        return DataTables::eloquent($invoices)
            ->addColumn('supplier.name', function ($invoice) {
                return $invoice->supplier ? $invoice->supplier->name : 'غير متوفر';
            })
            ->addColumn('store.name', function ($invoice) {
                return $invoice->store ? $invoice->store->name : 'غير متوفر';
            })
            ->toJson();
    }
$pageTitle = '';
$suppliers = Supplier::all();
$stores = Store::all();
//         $supplierProducts = SupplierInvoice::with('supplierInvoice')->get();
// dd($supplierProducts);
    return view('supplierInvoices.index' , compact('pageTitle' , 'suppliers' , 'stores'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pageTitle = 'Create Invoice';
        $stores = Store::all();
        $suppliers = Supplier::all();
        $supplierProducts = SupplierProduct::all();


        return view('supplierInvoices.add', compact('pageTitle'  , 'stores' , 'suppliers' , 'supplierProducts'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(StoreSupplierInvoiceRequest $request)
{
    DB::beginTransaction();
    try {
        // حساب المجموع الكلي من جميع المنتجات
        $total = 0;
        foreach ($request->items as $item) {
            if (!empty($item['product'])) {
                $total += $item['quantity'] * $item['price'];
            }
        }

        // حفظ الفاتورة الرئيسية
        $invoice = SupplierInvoice::create([
            'invoice_no'   => $request->invoice_no,
            'supplier_id'  => $request->supplier_id,
            'store_id'     => $request->store_id,
            'invoice_date' => $request->invoice_date,
            'pieces_no'    => $request->pieces_no ?? 0,
            'notes'        => $request->notes,
            'total'        => $total, // استخدام المجموع المحسوب
            'discount'        => $request->discount, // استخدام المجموع المحسوب
            'net_total'        => $total - $request->discount, // استخدام المجموع المحسوب
        ]);

        // حفظ تفاصيل المنتجات
        foreach ($request->items as $item) {
            if (empty($item['product'])) {
                continue;
            }

            SupplierInvoiceDetail::create([
                'supplier_invoice_id'   => $invoice->id,
                'supplier_product_id'   => $item['product'],
                'unit_type'             => $item['unit_type'] ?? 'piece',
                'quantity'              => $item['quantity'],
                'unit_price'            => $item['price'],
                'total_price'           => $item['quantity'] * $item['price'],
                'description'           => $item['notes'] ?? null,
            ]);
        }

        DB::commit();

        return redirect()->route('supplier_invoices.index')
                         ->with('success', __('invoice_added_successfully'));

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Failed to store supplier invoice', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
            'request' => $request->all()
        ]);

        return redirect()->back()
                         ->with('error', __('something_went_wrong'));
    }
}





    /**
     * Display the specified resource.
     */
    public function show(SupplierInvoice $supplierInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierInvoice $supplierInvoice)
    {
        $suppliers = Supplier::all();
        $stores = Store::all();
        $items = SupplierInvoice::with('items');
        $supplierProducts = SupplierProduct::all();
        return view('supplierInvoices.edit' , compact('supplierInvoice' , 'suppliers' , 'stores' , 'supplierProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(UpdateSupplierInvoiceRequest $request, SupplierInvoice $supplierInvoice)
{
    // تحديث بيانات الفاتورة
    $supplierInvoice->update([
        'invoice_no'    => $request->input('invoice_no'),
        'supplier_id'   => $request->input('supplier_id'),
        'store_id'      => $request->input('store_id'),
        'total'         => $request->input('total'),
        'net_total'         => $request->input('net_total'),
        'discount'         => $request->input('discount'),
        'pieces_no'     => $request->input('pieces_no'),
        'invoice_date'  => $request->input('invoice_date'),
        'notes'         => $request->input('notes'),
    ]);


        $supplierInvoice->items()->delete();

    // 3. إضافة المنتجات الجديدة
    foreach ($request->input('items', []) as $item) {
        $supplierInvoice->items()->create([
            'supplier_product_id' => $item['product'],
            'quantity'            => $item['quantity'],
            'unit_price'          => $item['price'],
            'total_price'         => $item['total_price'],
            'unit_type'           => $item['unit_type'] ?? 'piece',
        ]);
    }

    // إعادة توجيه إلى صفحة الفواتير مع رسالة نجاح
    return redirect()->route('supplier_invoices.index')
                     ->with('success', 'تم تحديث الفاتورة بنجاح');
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierInvoice $supplierInvoice)
    {
        //
    }
}
