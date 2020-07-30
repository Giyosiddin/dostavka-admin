<?php

namespace App\Http\Controllers\Api;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use App\Transformer\OrderTransformer;
use App\Http\Controllers\Controller;
use EllipseSynergie\ApiResponse\Laravel\Response;

class OrderController extends ApiController
{
      public function __construct(Response $response)
    {
        parent::__construct($response);
    }

    /**
     * @OA\Get(
     *     path="/orders",
     *     operationId="ApiOrderIndex",
     *     tags={"Order"},
     *     summary="Get orders list",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="List of orders"
     *     ),
     *     @OA\Parameter(
     *           ref="#/components/parameters/limit",
     *     ),
     *     @OA\Parameter(
     *          ref="#/components/parameters/page",
     *     ),
     *     @OA\Parameter(
     *          ref="#/components/parameters/include",
     *     )
     * )
     * 
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = auth()->user()->orders;
        return $this->response->get(['orders' => [$orders, new OrderTransformer]]);
    }

    /** 
     * @OA\Post(
     *     path="/orders",
     *     operationId="ApiOrderStore",
     *     tags={"Order"},
     *     summary="Update Order by auth",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ApiCartUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Order"
     *     )
     * )
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'phone' => 'required',
            'products' => 'required'
        ]);
        $products = $request->products;
        $qauntity = 0;
        foreach ($products as $key => $value) {
            $qauntity += $value['quantity'];
        }
        
        $order = Order::create([
            'name' => $request->name,
            'products' => $products,
            'phone' => $request->phone,
            'overal' =>  $qauntity
        ]);
        return $this->response->get(['order' => [$order, new OrderTransformer]]);
    }

    /** 
     * @OA\Get(
     *     path="/orders/{id}",
     *     operationId="ApiOrderShow",
     *     tags={"Order"},
     *     summary="Get order by id",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Order"
     *     ),
     *     @OA\Parameter(
     *          ref="#/components/parameters/id",
     *     ),     
     *     @OA\Parameter(
     *          ref="#/components/parameters/include",
     *     )
     * )
     * 
     * 
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order =Order::find($id);
        return $this->response->get(['order' => [$order, new OrderTransformer]]);
    }

    /** 
     * @OA\Put(
     *     path="/orders/{id}",
     *     operationId="ApiOrderUpdate",
     *     tags={"Order"},
     *     summary="Update Order by id",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ApiCartUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Order"
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
        $order = auth()->orders()->find($id);
        if ($order){
            $order->update($request->all());
        }
        return $this->response->get(['order' => [$order, new OrderTransformer]]);
    }

    /** 
     * @OA\Delete(
     *     path="/orders/{id}",
     *     operationId="ApiOrderDelete",
     *     tags={"Order"},
     *     summary="Get order by id",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Order"
     *     ),
     *     @OA\Parameter(
     *          ref="#/components/parameters/id",
     *     )
     * )
     * 
     * 
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = auth()->orders()->find($id);
        if ($order){
            $order->delete();
            return $this->json([
                'code' => 200,
                'data' => [
                    'message' => 'Successfully deleted order'
                ]
            ]);
        }
        return $this->json([
            'code' => 404,
            'data' => [
                'message' => 'Order not found'
            ]
        ]);
    }
}
