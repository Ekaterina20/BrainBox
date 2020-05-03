<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Need extends Model
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