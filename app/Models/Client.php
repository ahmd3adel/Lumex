<?php

namespace App\Models;

use App\Observers\GlobalModelObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    protected $table = 'clients';
    use HasFactory;
    public $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnGoods::class);
    }

    public function payments()
    {
        return $this->hasMany(ReceiptVoucher::class);
    }

        public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }
    protected static function booted()
    {
        static::addGlobalScope('client', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('agent')) {
                $storeId = Auth::user()->store_id;
                $builder->where('store_id', $storeId);
            }
        });

        parent::boot();
        static::observe(GlobalModelObserver::class);
    }



}
