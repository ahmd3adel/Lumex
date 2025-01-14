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
    public function get() :Collection{
       return Cart::with('product')->where('cookie_id' , $this->getCookie())->get();
    }
    public function add(Product $product , $quantity = 1){
        Cart::create([
            'cookie_id' => $this->getCookie(),
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }
    public function delete($id){
        Cart::where('id' , $id)->where('cart_id' , $this->getCookie())->delete();
    }
    public function update(Product $product , $quantity){
        Cart::where('product_id' , $product->id)->where('cart_id' , $this->getCookie())->update([
            'quantity' => $quantity
        ]);
    }
    public function total()
    {
        return Cart::where('cart_id' , $this->getCookie())->join('products', 'products.id', '=', 'cart.product_id')
            ->selectRaw('SUM(products.price * cart.quantity) as total_price')
            ->value('total_price') ?? 0;
    }
    public function empty()
    {
        Cart::where('user_id', Auth::id())->delete();
    }
    protected function getCookie()
    {
        $cookie_id = Cookie::get('cart_id');
        if(!$cookie_id)
        {
            Cookie::queue('cart_id' , $cookie_id , 30 * 24 * 60);
        }
        return $cookie_id;
    }
}
?>
