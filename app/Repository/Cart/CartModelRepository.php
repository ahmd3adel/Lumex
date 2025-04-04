<?php
namespace App\Repository\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Repository\Cart\CartRepository;
use Carbon\Carbon;
use \Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartModelRepository implements CartRepository
{
    public $items;
    public function __construct()
    {
        $this->items = collect([]);
    }
    public function get() :Collection{
        if (!$this->items->count())
        {
             $this->items = Cart::with('product')->get();
        }
       return $this->items;
    }
    public function add(Product $product , $quantity = 1){
        $item= Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
        $this->get()->push($item);
    }
    public function delete($id){
        Cart::where('id' , $id)->delete();
    }
    public function update(Product $product , $quantity){
        Cart::where('product_id' , $product->id)->update([
            'quantity' => $quantity
        ]);
    }
    public function total()
    {
         return $this->get()->sum(function ($item){
            return $item->quantity * $item->price;
        });
    }
    public function empty()
    {
        Cart::where('user_id', Auth::id())->delete();
    }

}
?>
