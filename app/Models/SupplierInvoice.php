<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoice extends Model
{
    use HasFactory;
        protected $guarded = [];

            protected $casts = [
        'invoice_date' => 'date',
        'invoice_date' => 'date:Y-m-d',
    ];



        public function store()
    {
        return $this->belongsTo(Store::class);
    }

            public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

public function items()
{
    return $this->hasMany(SupplierInvoiceDetail::class, 'supplier_invoice_id');
}


}
