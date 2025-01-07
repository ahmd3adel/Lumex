<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    protected static function booted()
    {
        static::addGlobalScope('store', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('agent')) {
                $storeId = Auth::user()->store_id;
                $builder->where('store_id', $storeId);
            }
        });
    }


}
