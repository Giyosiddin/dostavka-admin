<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
    protected $fillable = [
        'id',
        'name',
        'phone',
        'status',
        'country_id',
        'delivery_info',
        'overal',
        'meta',
    ];
      /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */


    public function products(){
        return $this->belongsToMany('App\Product')->withPivot(['total', 'cost', 'quantity']);
    }

}
