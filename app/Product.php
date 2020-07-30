<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Product extends Model implements HasMedia
{

    protected $fillable=["title",'cost', 'meta', 'brand_id', 'description', 'vendor_id'];
    use HasMediaTrait;

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('gallary');
    }
    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }
    public function products()
    {
        return $this->hasMany('App\Product','parent_id', 'id');
    }
    public function parent(){
        return $this->belongsTo('App\Product', 'parent_id', 'id');
    }
    public function properties()
    {
        return $this->hasMany('App\ProductProperty');
    }
    public function vendor(){
        return $this->belongsTo('App\User');
    }
}
