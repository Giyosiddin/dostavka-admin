<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use App\Transformer\ProductTransformer;
use App\Transformer\CategoryTransformer;
use EllipseSynergie\ApiResponse\Laravel\Response;

class CategoryController extends ApiController
{ 

    /**
     * @OA\Get(
     *     path="/categories",
     *     operationId="ApiCategoryIndex",
     *     tags={"Category"},
     *     summary="Get categories list",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="List of categories"
     *     ),
     *     @OA\Parameter(
     *           ref="#/components/parameters/limit",
     *     ),
     *     @OA\Parameter(
     *          ref="#/components/parameters/page",
     *     ),
     *     @OA\Parameter(
     *          ref="#/components/parameters/include",
     *     ),     
     *     @OA\Parameter(
     *          ref="#/components/parameters/parent_id",
     *     )
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parent_slug = request()->get('parent', false);
        $ids = request()->get('ids', false);
        $bazars = request()->get('bazars', false);

        $query = Category::query();
        if ($ids){
            $categories = $query->whereIn('id', $ids)->get();
                return $this->response->get(['categories' => [$categories, new CategoryTransformer]]);
        }

        if($bazars){
            $query->whereHas('bazars', function($query_in) use ($bazars){ 
                $query_in->whereIn('id', $bazars);
            });
            $categories = $query->get();
            return $this->response->get(['categories' => [$categories, new CategoryTransformer]]);
        }

        $categories = [];
        if ($parent_slug){
          $category = $query->where('slug', $parent_slug)->first();
          if ($category){
              $categories = $query->descendantsOf($category->id)->toTree($category->id);
          }
        } else{
          $categories =  $query->where('parent_id', request()->get('parent_id', null))->get();
        }

        return $this->response->get(['categories' => [$categories, new CategoryTransformer]]);
    }


    /** 
     *@OA\Get(
     *     path="/categories/{id}",
     *     operationId="ApiCategoryShow",
     *     tags={"Category"},
     *     summary="Get categories by id",
     *     security={
     *       {"token": {}},
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Category"
     *     ),
     *     @OA\Parameter(
     *          ref="#/components/parameters/id",
     *     ),     
     *     @OA\Parameter(
     *          ref="#/components/parameters/include",
     *     )
     * )
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
      return $this->response->get(['category' => [$category, new CategoryTransformer]]);   
    }


    public function categoryProducts($id)
    {
        $category = Category::find($id);
        $products = $category->products;
         return $this->response->get(['products' => [$products, new ProductTransformer]]);   
    }
}
