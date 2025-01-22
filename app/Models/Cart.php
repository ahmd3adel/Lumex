<?php

namespace App\Models;

use App\Observers\CartObsesrver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::observe(CartObsesrver::class);
        static::addGlobalScope('cookie' , function (Builder $builder){
            $builder->where('cookie_id' , Cart::getCookie());
        });
    }

    public  function user()
    {
        return $this->belongsTo(User::class);
    }

    public  function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getCookie()
    {
        $cookie_id = Cookie::get('cart_id');
        if(!$cookie_id)
        {
            Cookie::queue('cart_id' , $cookie_id , 30 * 24 * 60);
        }
        return $cookie_id;
    }
}
