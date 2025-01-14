<?php

namespace App\Models;

use App\Observers\CartObsesrver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::observe(CartObsesrver::class);
    }

    public  function user()
    {
        return $this->belongsTo(User::class);
    }

    public  function product()
    {
        return $this->belongsTo(Product::class);
    }
}
