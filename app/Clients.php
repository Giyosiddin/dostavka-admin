<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{	

	public $fillable = ['phone','name'];


    public function orders()
    {
         return $this->hasMany(
            'App\Order','phone','phone', 
        );
    }
}
