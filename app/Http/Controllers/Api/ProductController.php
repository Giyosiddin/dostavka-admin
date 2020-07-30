<?php

namespace App\Http\Controllers\Api;

use App\Product;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  App\Transformer\ProductTransformer;
use App\Http\Controllers\Api\ApiController;
use EllipseSynergie\ApiResponse\Laravel\Response;

class ProductController extends ApiController
{
    public function __constructor(Response $response) {
        parent::__constructor($response);
        $this->middleware('auth:api', ['except' => ['login','registration']]);
    }
    /**
     * @OA\Get(
     *     path="/products",
     *     operationId="ApiProductIndex",
     *     tags={"Product"},
     *     summary="Get products list",
     *     @OA\Response(
     *         response="200",
     *         description="List of products"
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
        $products = Product::query()->limit(request()->get('limit', 10));
        if (is_array(request()->get('categories'))){
            $categories = request()->get('categories');
            $products->whereHas('categories', function($query) use ($categories){
                $query->whereIn('categories.id', $categories);
            });
        }
        if(is_array(request()->get('vendor_id'))){
            $vendor_id = request()->get('vendor_id');
            $products->whereIn('vendor_id',$vendor_id);
        }



        $products = $products->get();
        return $this->response->get(['products' => [$products, new ProductTransformer]]);
    }

    /** 
     * @OA\Get(
     *     path="/products/{id}",
     *     operationId="ApiProductShow",
     *     tags={"Product"},
     *     summary="Get product by id",
     *     @OA\Response(
     *         response="200",
     *         description="Product"
     *     ),
     *     @OA\Parameter(
     *          ref="#/components/parameters/id",
     *     ),     
     *     @OA\Parameter(
     *          ref="#/components/parameters/include",
     *     )
     * )
     * 
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->response->get(['product' => [$product, new ProductTransformer]]);
    }

    public function store(Request $request)
    {

        $vendor_id = Auth::user()->id;
        $data = [
            'title' => $request->title,
            'cost' => $request->cost,
            'brand_id'  => $request->brand_id,
            'description' => $request->description,
            'vendor_id'  =>  $vendor_id
        ];
        $product = Product::create($data);
        foreach ($request->gallary as $file) {
            $gallary = $product->addMedia($file)->toMediaCollection('gallary');
        }
           
    }

    public function destroy($id)
    {
        $destroy = Product::destroy($id);

        return ['massage' => "Deleted Product"]; 
    }


    public function update(Request $request,$id)
    {
        $vendor_id = auth()->user()->id;
        $data = [
            'title' => $request->title,
            'cost' => $request->cost,
            'brand_id'  => $request->brand_id,
            'description' => $request->description
        ];
        $product = Product::find($id);
        $product->where('vendor_id','=', $vendor_id)->update($data);
        if($request->deleted_files){
            $deleted_files = $request->deleted_files;
            $deleted_media = $product->getMedia('gallary')->whereIn('id',[$deleted_files]);            
            foreach($deleted_media as $del_media){
                $del_media->delete();
            }
        }
        if($request->gallary) {
            foreach ($request->gallary as $file) {
                $gallary = $product->addMedia($file)->toMediaCollection('gallary');
            }
        }
        return $this->response->get(['product' => [$product, new ProductTransformer]]);
       
    }

    public function filter()
    {
        $products = Product::query();
        if(request()->title){
            $keyword = request()->title;
            $products->where('title', 'like', '%'.$keyword.'%');
        }
        if(request()->categories){
            $categories = request()->get('categories');
            $products->whereHas('categories', function($query) use ($categories){
                $query->whereIn('categories.id', $categories);
            });
        }
        if(request()->min_price && request()->max_price){
            $min_price = request()->min_price;
            $max_price = request()->max_price;
            $products->whereBetween('cost', [$min_price, $max_price]);
        }
        $products = $products->get();
        return $this->response->get(['products' => [$products, new ProductTransformer]]);
    }
}
