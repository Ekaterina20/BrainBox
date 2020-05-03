<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class GameResult extends Model
{
  //======================================================================
  // Settings
  //======================================================================
  
  protected $fillable = [
    'game_id',
    'team_id',
  ];

  protected $hidden = [
    'cur_level',
    'game_id',
    'team_id',
    'created_at',
    'updated_at',
    'pivot'
  ];

  protected $appends = [
    'team'
  ];

  //======================================================================
  // Relationships
  //======================================================================

  public function game()
  {
    return $this->belongsTo('App\Game');
  }

  public function team()
  {
    return $this->belongsTo('App\Team');
  }

  //======================================================================
  // Appends
  //======================================================================

  public function getTeamAttribute()
  {
    return $this->team()->first();
  }

  public function getGameAttribute()
  {
    return $this->game()->select('id', 'name', 'preview', 'private')->first();
  }

  //======================================================================
  // Getters
  //======================================================================
  
  public function getTimeAttribute($time)
  {
    if (!$time) return null;
    $h = floor($time / 3600);
    $m = floor(($time - $h * 3600) / 60);
    $s = $time - $m * 60 - $h * 3600;
    return $h . ':' . $m . ':' . $s;
  }

  //======================================================================
  // Methods
  //======================================================================
  
}
