<?php
namespace App\Repository\Cart;

use App\Models\Product;
use Illuminate\Support\Collection;

interface CartRepository
{
    public function get() :Collection;
    public function add(Product $product , $quantity = 1);
    public function delete($id);
    public function update(Product $product , $quantity);
    public function total();
    public function empty();

}


?>
