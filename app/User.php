<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use \Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

use App\Team;
use App\TeamRequest;
use App\TeamInvite;

class User extends Authenticatable
{
  use Notifiable;

  //======================================================================
  // Variables
  //======================================================================

  public const PATH = [
    'avatar' => '/uploads/u/',
    'avatar-default' => '/uploads/u/default.png',
  ];

  public const ROLE = [
    'admin'
  ];

  //======================================================================
  // Modes Settings
  //======================================================================

  protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'api_token',
  ];

  protected $hidden = [
    'email',
    'phone',
    'password',
    'api_token',
    // ------
    'pivot',
    'created_at',
    'updated_at',
    'email_verified_at',
  ];

  protected $casts = [
    'team_id' => 'integer'
  ];

  //======================================================================
  // Relationships
  //======================================================================

  public function team()
  {
    return $this->belongsTo('App\Team');
  }

  public function requests()
  {
    return $this->hasMany('App\TeamRequest');
  }

  public function invites()
  {
    return $this->hasMany('App\TeamInvite');
  }

  //======================================================================
  // Appends
  //======================================================================

  public function getAvatarAttribute($avatar)
  {
    return $avatar ?
      asset(static::PATH['avatar'] . $avatar) :
      asset(static::PATH['avatar-default']);
  }

  public function getRequestsAttribute()
  {
    return $this->requests()
      ->get()
      ->each(function($request) {
        return $request->append('team');
      });
  }

  public function getInvitesAttribute()
  {
    return $this->invites()
      ->get()
      ->each(function($invite) {
        return $invite->append('team');
      });
  }

  public function getTeamAttribute()
  {
    return $this->team()->first();
  }

  //======================================================================
  // Methods
  //======================================================================

  public static function list() {
    return static::select('id', 'name', 'avatar')
      ->where([
        ['team_id', '=', null],
        ['id', '!=', Auth::id()]
      ])
      ->paginate(30);
  }

  public static function search($query) {
    return static::select('id', 'name', 'avatar')
      ->where([
        ['name', 'like', '%' . $query . '%'],
        ['team_id', '=', null],
        ['id', '!=', Auth::id()]
      ])
      ->paginate(30);
  }

  public static function add($data)
  {
    return static::create([
      'name' => $data['name'],
      'phone' => $data['phone'],
      'password' => Hash::make($data['password']),
      'api_token' => Str::random(60),
    ])->id;
  }

  public function leaveTeam()
  {
    $this->team_id = null;
    $this->save();
  }

  public function sendTeamRequest($id)
  {
    return $this->requests()->create(['team_id' => $id]);
  }

  public function profile()
  {
    // dd(Team::find($this->team_id));
    return $this->load('team');
  }

  public function details()
  {
    return $this->load('team')->makeVisible([
      'email',
      'phone',
      'api_token'
    ]);
  }

  public function updateAvatar(UploadedFile $img)
  {

    $this->removeAvatar();

    $filename = Str::random(10) . '.' . $img->extension();
    $img->storeAs(static::PATH['avatar'], $filename);

    $this->avatar = $filename;
    $this->save();

    return $this->avatar;
  }

  public function removeAvatar()
  {
    if (!$this->attributes['avatar']) return;
    Storage::delete(static::PATH['avatar'] . $this->attributes['avatar']);
  }

  //======================================================================
  // Helpers
  //======================================================================

  public function hasRole($role)
  {
    return DB::table('roles')->where([
      ['user_id', $this->id],
      ['role', array_flip(static::ROLE)[$role]]
    ])->exists();
  }
}
