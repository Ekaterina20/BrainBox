<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
  //======================================================================
  // Settings
  //======================================================================
  
  protected $fillable = [
    'team_id',
    'level_id',
    'code',
    'right'
  ];

  //======================================================================
  // Relationships
  //======================================================================
  
  public function level()
  {
    $this->belongsTo('App\Level');
  }
}
