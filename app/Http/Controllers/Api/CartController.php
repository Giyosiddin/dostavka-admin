<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use App\Transformer\CartTransformer;
use App\Transformer\ProductTransformer;
use EllipseSynergie\ApiResponse\Laravel\Response;

class CartController extends ApiController
{
    public function __constructor(Response $response) {
      parent::__constructor($response);
      $this->middleware('auth:api');

    }
    /** 
     * @OA\Get(
     *     path="/carts",
     *     operationId="ApiCartShow",
     *     tags={"Cart"},
     *     summary="Get Cart by auth",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Cart"
     *     ),    
     *     @OA\Parameter(
     *          ref="#/components/parameters/include",
     *     )
     * )
     * 
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $ids = request()->get('products');
        $products = Product::whereIn('id', $ids)->get();
        return $this->response->get(['products' => [$products, new ProductTransformer]]);
    }

    /** 
     * @OA\Put(
     *     path="/carts",
     *     operationId="ApiCartUpdate",
     *     tags={"Cart"},
     *     summary="Update Cart by auth",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ApiCartUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Cart"
     *     )
     * )
     * 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$cart->update(['meta' => $request->meta]);

		return $this->response->get(['cart' => [$cart, new CartTransformer]]);
    }

     /** 
     * @OA\Delete(
     *     path="/carts",
     *     operationId="ApiCartDelete",
     *     tags={"Cart"},
     *     summary="Delete Cart by auth",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Cart"
     *     )
     * )
     * 
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $cart = auth()->user->cart;
        if ($cart){
            $cart->update(['meta' => null]);

            return $this->response->get(['cart' => [$cart, new CartTransformer]]);
        }
    }

    public function addToCart()
    {
        $meta = request()->meta;
        $data = [
            'user_id' => auth()->user()->id,
            'meta'    => $meta,
        ];

        $create = Cart::create($data);
        if($create){
            $cart = auth()->user()->cart;
            $cart = $cart->all();
            return $this->response->get(['cart' => [$cart, new CartTransformer]]);
        }
         return $this->response->get(['error' => "Product not added"]);
    }
}
