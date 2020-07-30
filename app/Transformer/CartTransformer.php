<?php
namespace App\Transformer;

use App\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['products'];
    public function transform(Cart $cart)
    {
        return [
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'meta' => json_decode($cart->meta, true),
        ];
    }
    public function includeProducts(Cart $cart){
      $products = $cart->products;
      if ($products)
          return $this->collection($products, new ProductTransformer);
    }
}