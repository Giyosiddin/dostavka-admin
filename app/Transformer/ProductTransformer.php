<?php
namespace App\Transformer;

use App\Product;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;

class ProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['gallery', 'cover_image', 'vendor'];
    protected $defaultIncludes = ['cover_image'];

    public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'title' => $product->title,
            'description' => $product->description,
            'cost' => intval($product->cost),
            'vendor_id' => $product->vendor_id,
            'meta' => json_decode($product->meta, true),
        ];
    }
    public function includeCoverImage(Product $product)
    {
        $images = $product->getMedia('gallary');
        $cover = null;
        if (count($images) > 0){
            $cover = $images[0];
        }
        if ($cover)
            return $this->item($cover, new MediaTransformer, 'data');
    }
    public function includeGallery(Product $product)
    {
      return $this->collection($product->getMedia('gallary'), new MediaTransformer);
    }
    public function includeVendor(Product $product)
    {
        if ($product->vendor)
            return $this->item($product->vendor, new UserTransformer);
    }
}