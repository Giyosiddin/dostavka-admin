<?php
namespace App\Transformer;

use App\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['products'];
    public function transform(Order $order)
    {
        return [
            'id' => $order->id,
            'name' => $order->name,
            'phone' => $order->phone,
            'status' => $order->status,
            'country_id' => $order->country_id,
            'cart_products' => $order->products,
            'delivery_info' => $order->delivery_info,
            'overal' => $order->overal,
            'meta' => $order->meta,
        ];
    }
    public function includeProducts(Order $order){
        $products = \App\Product::whereIn('id', array_column($order->products, 'id'))->get();
        return $this->collection($products, new ProductTransformer);

    }
}