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
        'products',
        'delivery_info',
        'overal',
        'meta',
    ];
      /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'products' => 'array',
    // ];

    protected $casts = [
        'products' => 'json',
    ];

    public function products(){
        return $this->belongsToMany('App\Product');

    }

}
