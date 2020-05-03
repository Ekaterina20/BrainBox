<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Level extends Model
{
  //======================================================================
  // Settings
  //======================================================================
  
  protected $fillable = [
    'game_id',
    'required',
    'order',
    'jump',
  ];

  protected $hidden = [
    'game_id',
    'game',
    'updated_at',
    'created_at',
  ];

  protected $casts = [
    'jump' => 'integer'
  ];

  //======================================================================
  // Relationships
  //======================================================================
  
  public function attachments()
  {
    return $this->hasMany('App\Attachment');
  }

  public function answers()
  {
    return $this->hasMany('App\Answer');
  }

  public function helps()
  {
    return $this->hasMany('App\Help');
  }

  public function game()
  {
    return $this->belongsTo('App\Game');
  }

  public function attempts()
  {
    return $this->hasMany('App\Attempt');
  }

  // User's team attempts
  public function getAttemptsAttribute()
  {
    return $this->attempts()->where('team_id', Auth::user()->team_id);
  }

  //======================================================================
  // Appends
  //======================================================================

  public function getAttachsAttribute()
  {
    return $this->attachments()->orderBy('order', 'asc')->get();
  }

  public function getAnsweredAttribute()
  {
    return $this->answers()
      ->whereIn('code', $this->attempts->pluck('code'))
      ->get();
  }

  public function getHelpsAttribute()
  {
    return $this->helps()->orderBy('delay', 'asc')->get();
  }

  public function getCompleteAttribute()
  {
    return $this->attempts->count() >= $this->required;
  }

  //======================================================================
  // Methods
  //======================================================================

  public static function add($data)
  {
    $level = new static;
    $level->fill($data);

    static::where([
      ['game_id', $data['game_id']],
      ['order', '>=', $data['order']]
    ])->increment('order');

    return $level->save();
  }

  public function remove()
  {
    static::where([
      ['game_id', $this->game_id],
      ['order', '>', $this->order]
    ])->decrement('order');

    return $this->delete();
  }

  public function details()
  {
    return $this->append(['answered', 'attachs', 'helps']);
  }

  public function attempt($code)
  {
    $answer = $this->answers()->where('code', $code)->first();

    if (!$answer) {
      return [
        'status' => 'fail',
        'answered' => $this->answered
      ];
    }

    if ($this->hasAttempt($code)) {
      return [
        'status' => 'already',
        'answered' => $this->answered
      ];
    }

    $this->makeAttempt($code);

    return [
      'status' => 'success',
      'answered' => $this->answered
    ];
  }

  //======================================================================
  // Helpers
  //======================================================================

  public function makeAttempt($code)
  {
    $this->attempts()->create([
      'code'      => $code,
      'team_id'   => Auth::user()->team_id
    ]);
  }

  public function hasAttempt($code)
  {
    return $this->attempts()->where([
      ['team_id', Auth::user()->team_id],
      ['code', $code]
    ])->exists();
  }
}
