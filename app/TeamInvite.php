<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TeamInvite extends Model
{
  //======================================================================
  // Variables
  //======================================================================
  
  public const STATUS = [
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
    'user_id'
  ];

  protected $hidden = [
    'user_id',
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

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  //======================================================================
  // Appends
  //======================================================================
  
  public function getTeamAttribute()
  {
    return $this->team()->select('id', 'name')->first();
  }

  public function getUserAttribute()
  {
    return $this->user()->first();
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
    $this->attributes['status'] = array_flip(static::STATUS)[$value];
  }

  //======================================================================
  // Methods
  //======================================================================
  
  public function accept()
  {
    $this->status = 'accepted';
    $this->save();
    return $this->makeVisible('team_id');
  }

  public function reject()
  {
    $this->status = 'rejected';
    $this->save();
    return $this;
  }

  public function cancel()
  {
    $this->status = 'canceled';
    $this->save();
    return $this;
  }
}
