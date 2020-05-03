<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Carbon;

class Team extends Model
{
  //======================================================================
  // Variables
  //======================================================================
  
  public const SIZE = ['min' => 3, 'max' => 20];

  //======================================================================
  // Model Settings
  //======================================================================
  
  protected $fillable = [
    'name',
    'user_id',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
    'pivot'
  ];

  protected $casts = [
    'user_id' => 'integer'
  ];

  //======================================================================
  // Relationships
  //======================================================================
  
  public function users()
  {
    return $this->hasMany('App\User');
  }

  public function requests()
  {
    return $this->hasMany('App\TeamRequest');
  }

  public function invites()
  {
    return $this->hasMany('App\TeamInvite');
  }

  public function invitedUsers()
  {
    return $this->belongsToMany('App\User', 'team_invites');
  }

  public function games()
  {
    return $this->belongsToMany('App\Game', 'game_results');
  }

  public function results()
  {
    return $this->hasMany('App\GameResult');
  }

  public function gameRequests()
  {
    return $this->hasMany('App\GameRequest');
  }

  //======================================================================
  // Appends
  //======================================================================

  public function getUsersAttribute()
  {
    return $this->users()->select('id', 'name', 'avatar')->get();
  }

  public function getRequestsAttribute()
  {
    return $this->requests()
      ->where('status', 0)
      ->get()
      ->each(function($request) {
        return $request->append('user');
      });
  }

  public function getInvitesAttribute()
  {
    return $this->invites()
      ->get()
      ->each(function($invite) {
        return $invite->append('user');
      });
  }

  /*
  * Вот эту вот парашу, исправить! Я пока хз как.
  */
  public function getFgamesAttribute()
  {
    return $this->games()
      ->where('date_start', '>', now())
      ->get()
      ->each(function($g) {
        return $g->makeHidden([
          'type',
          'price',
          'area',
          'date_start',
          'date_end',
          'teams_cnt',
        ]);
      });
  }

  public function getResultsAttribute()
  {
    return $this->results()->where('time', '!=', null)->get()
      ->each(function($result) {
        return $result->setAppends(['game']);
      });
  }

  /*
  * Ира в которую команда играет в данный момент
  * или до старта которой осталось менее 15 минут
  */
  public function getGameAttribute()
  {
    return $this->games()
      ->where([
        ['date_start', '<', now()->addMinutes(15)],
        ['date_end', null]
      ])
      ->first();
  }

  //======================================================================
  // Methods
  //======================================================================

  public static function list()
  {
    return static::select('id', 'name')
      ->paginate(30);
  }

  public static function search($query) {
    return static::select('id', 'name')
      ->where('name', 'like', '%' . $query . '%')
      ->paginate(30);
  }

  public function details()
  {
    return $this->append(['users', 'requests', 'invites']);
  }

  public function profile()
  {
    return $this->append(['results', 'fgames', 'users']);
  }

  public function inviteUsers($ids)
  {
    $this->invitedUsers()->attach($ids);
  }

  public function makeLeader($id)
  {
    $this->user_id = $id;
    $this->save();
  }

  public function sendGameRequest($id)
  {
    return $this->gameRequests()->create(['game_id' => $id]);
  }
}
