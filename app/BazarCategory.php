<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BazarCategory extends Model
{
	protected $table = "bazar_category";
    
    protected $fillable = ['bazar_id', 'category_id'];
}
