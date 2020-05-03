<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GameRequest extends Model
{
  //======================================================================
  // Variables
  //======================================================================
  
  protected const STATUS = [
    0 => 'pending',
    1 => 'accepted',
    2 => 'rejected',
    3 => 'canceled',
  ];

  //======================================================================
  // Settings
  //======================================================================
  
  protected $fillable = [
    'team_id',
    'game_id'
  ];

  protected $hidden = [
    'game_id',
    'team_id',
    'created_at',
    'updated_at'
  ];

  //======================================================================
  // Relationships
  //======================================================================
  
  public function team()
  {
    return $this->belongsTo('App\Team');
  }

  public function game()
  {
    return $this->belongsTo('App\Game');
  }

  //======================================================================
  // Appends
  //======================================================================
  
  public function getTeamAttribute()
  {
    return $this->team()->select('id', 'name')->first();
  }

  public function getGameAttribute()
  {
    return $this->game()->select('id', 'name', 'perview', 'private')->first();
  }

  //======================================================================
  // Getters
  //======================================================================
  
  public function getStatusAttribute($status)
  {
    return static::STATUS[$status];
  }

  //======================================================================
  // Setters
  //======================================================================
  
  public function setStatusAttribute($value)
  {
    return array_flip(static::STATUS[$status])[$value];
  }

  //======================================================================
  // Methods
  //======================================================================
  
  public function accept()
  {
    // Soon
  }

  public function reject()
  {
    $this->status = 'rejected';
    $this->save();
  }

  public function cancel()
  {
    $this->status = 'canceled';
    $this->save;
  }
}
