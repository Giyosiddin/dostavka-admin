<?php
namespace App\Transformer;

use App\Bazar;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;

class BazarTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];
    public function transform(Bazar $bazar)
    {
        return [
            'id' => $bazar->id,
            'name' => $bazar->name,
            'slug' => $bazar->slug,
            'description' => $bazar->description,
            'address' => $bazar->address,
            'cover_image' => url(Storage::url($bazar->image))
        ];
    }
}