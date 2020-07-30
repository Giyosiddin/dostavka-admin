<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
  use NodeTrait;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title', 'slug', 'parent_id', 'is_active', 'order', 'left', 'right', 'image'
  ];

  public function parent(){
    return $this->belongsTo('App\Category','parent_id');
  }
  public function children(){
    return $this->hasMany('App\Category','parent_id');
  }
  public function getLftName()
  {
      return 'left';
  }
  
  public function getRgtName()
  {
      return 'right';
  }
  
  public function getParentIdName()
  {
      return 'parent_id';
  }
  public function products()
  {
    return $this->belongsToMany('App\Product');
  }
  public function properties()
  {
    return $this->belongsToMany('App\Property');
  }
  public function bazars(){
    return $this->belongsToMany('App\Bazar');
  }
}
