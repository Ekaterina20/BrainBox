<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
  //======================================================================
  // Settings
  //======================================================================
  
  protected $hidden = [
    'level_id',
    'created_at',
    'updated_at'
  ];

  protected $fillable = [
    'level_id',
    'delay',
    'text'
  ];

  protected $casts = [
    'delay' => 'integer'
  ];

  //======================================================================
  // Relationships
  //======================================================================
  
  public function level()
  {
    return $this->belongsTo('App\Level');
  }

  //======================================================================
  // Getters
  //======================================================================
  
  public function getTextAttribute($text)
  {
    return $this->isReady() ? $text : null;
  }

  //======================================================================
  // Helpers
  //======================================================================
  
  public function getText()
  {
    $this->delay = 0;
    return $this->text;
  }

  public function isReady()
  {
    return !$this->delay || $this->delay + floor($this->level()->first()->game->timer / 60) <= 0;
  }
}
