<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoiceDetail extends Model
{
    use HasFactory;
        protected $guarded = [];

            public function supplierInvoice()
    {
        return $this->belongsTo(SupplierInvoice::class);
    }

}
