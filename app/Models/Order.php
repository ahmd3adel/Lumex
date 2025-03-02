<?php

namespace App\Models;

use App\Observers\GlobalModelObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    public function products()
    {
        return $this->belongsToMany(Product::class , 'order_items' , 'order_id' , 'product_id' )
            ->withPivot([
                'product_name' , 'price'
            ]);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'guest'
        ]);
    }



    protected static function booted()
    {
        static::creating(function (Order $order){

        });

    }

    public static function getNextOrderNumber()
    {
        $year = date('y');
        $number = Order::whereYear('create_at' , $year)->max('number');
        if($number)
        {
            return $number + 1;
        }
        return  $year;
    }

}
