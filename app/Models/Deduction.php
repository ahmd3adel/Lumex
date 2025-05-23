<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\GlobalModelObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
class Deduction extends Model
{
        use HasFactory;
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

        public function store()
    {
        return $this->belongsTo(Store::class);
    }


        protected static function booted()
    {
        static::addGlobalScope('deduction', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('agent')) {
                $storeId = Auth::user()->store_id;
                $builder->where('store_id', $storeId);
            }
        });

        parent::boot();
        static::observe(GlobalModelObserver::class);
    }
}
