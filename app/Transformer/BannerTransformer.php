<?php
namespace App\Transformer;

use App\Banner;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;

class BannerTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['products'];
    public function transform(Banner $banner)
    {
      
      return [
            'id' => $banner->id,
            'title' => $banner->title,
            'url' => $banner->url,
            'description' => $banner->desc,
            'image' => url(Storage::url($banner->image))
        ];
    }
}