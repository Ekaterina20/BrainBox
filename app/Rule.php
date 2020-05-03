<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
  //======================================================================
  // Settings
  //======================================================================
  
  protected $fillable = [
    'text'
  ];

  protected $hidden = [
    'pivot'
  ];

  public $timestamps = false;
}
