<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    use HasFactory;
    public $guarded = [];

    public function store()
    {
        return $this->belongsTo(Client::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('client', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('agent')) {
                $storeId = Auth::user()->store_id;
                $builder->where('store_id', $storeId);
            }
        });
    }



}
