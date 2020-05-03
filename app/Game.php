<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Attempt;
use \Storage;
use Illuminate\Support\Carbon;
use App\Answer;
use App\Attachment;
use App\Help;

class Game extends Model
{
  //======================================================================
  // Variables
  //======================================================================

  public const TYPE = [
    'liner',
    'storm',
  ];

  private const PATH = [
    'preview' => '/uploads/g/',
    'preview-default' => '/uploads/g/default.png'
  ];

  //======================================================================
  // Model Settings
  //======================================================================
  
  protected $fillable = [
    'name',
    'type',
    'user_id',
    'price',
    'area',
    'date_start',
    'private'
  ];

  protected $hidden = [
    'user_id',
    'private',
    'created_at',
    'updated_at',
    'pivot',
  ];

  protected $appends = [
    'premit',
    'teams_cnt'
  ];

  protected $casts = [
    'date_start' => 'datetime:d.m.Y H:i',
    'date_end' => 'datetime:d.m.Y H:i',
  ];

  //======================================================================
  // Relationships
  //======================================================================

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function needs()
  {
    return $this->belongsToMany('App\Need');
  }

  public function rules()
  {
    return $this->belongsToMany('App\Rule');
  }

  public function levels()
  {
    return $this->hasMany('App\Level');
  }

  public function results()
  {
    return $this->hasMany('App\GameResult');
  }

  //======================================================================
  // Appends
  //======================================================================

  public function getTeamsCntAttribute()
  {
    return $this->results()->count();
  }

  public function getPreviewAttribute($preview)
  {
    return $preview ?
      asset(static::PATH['preview'] . $preview) :
      asset(static::PATH['preview-default']);
  }

  public function getAuthorAttribute()
  {
    return $this->user()->select('id', 'name')->first();
  }

  public function getLevelsCntAttribute()
  {
    return $this->levels()->count();
  }

  public function getResultAttribute()
  {
    return $this->results()
      ->where('team_id', Auth::user()->team_id)
      ->first()
      ->setAppends([])
      ->makeVisible('cur_level');
  }

  public function getTimerAttribute()
  {
    $result = $this->result;

    if ($this->type == 'storm' || $result->cur_level == 1) {
      return now()->diffInSeconds($this->date_start, false);
    } else {
      return now()->diffInSeconds($result->updated_at, false);
    }
  }

  public function getPremitAttribute()
  {
    return $this->private ?
      DB::table('game_premits')->where([
        ['game_id', $this->id],
        ['team_id', Auth::user()->team_id]
      ])->exists() : true;
  }

  public function getDateStartAttribute($val)
  {
    return Carbon::parse($val)->format('d.m.Y H:i');
  }

  public function getDateEndAttribute($val)
  {
    return $val ? Carbon::parse($val)->format('d.m.Y H:i') : null;
  }

  //======================================================================
  // Getters
  //======================================================================
  
  public function getTypeAttribute($val)
  {
    return static::TYPE[$val];
  }

  public function getLevelsAttribute()
  {
    return $this->levels()
      ->orderBy('order')
      ->get()->each(function($l) {
        return $l->append('complete');
      });
  }

  //======================================================================
  // Setters
  //======================================================================
  
  public function setTypeAttribute($val)
  {
    $this->attributes['type'] = array_flip(static::TYPE)[$val];
  }

  public function setDateStartAttribute($val)
  {
    $this->attributes['date_start'] = Carbon::createFromFormat('d.m.Y H:i', $val)->toDateTimeString();
  }

  //======================================================================
  // Methods
  //======================================================================

  public static function list()
  {
    return static::select([
        'id',
        'name',
        'preview',
        'price',
        'date_start',
        'date_end',
        'private'
      ])
      ->where('removed', 0)
      ->paginate(15);
  }

  public static function search($query)
  {
    return static::select([
        'id',
        'name',
        'preview',
        'price',
        'date_start',
        'date_end',
        'private'
      ])
      ->where('removed', 0)
      ->where('name', 'like', '%' . $query . '%')
      ->paginate(15);
  }

  public function details()
  {
    return $this->load(['rules', 'needs', 'results'])->append('author');
  }

  public function state()
  {
    return $this->append(['result', 'timer', 'levels_cnt']);
  }

  public function getLevelN($order)
  {
    $level = $this->levels()->where('order', $order)->first();
    return $level ? $level->details() : null;
  }

  public function getCurrentLevel()
  {
    return $this->getLevelN($this->result->cur_level);
  }
  
  public function jumpNextLevel()
  {
    $result = $this->result;
    $result->cur_level++;
    $result->save();
  }

  public function setPreview($img)
  {
    if (!$img) return;

    if (isset($this->attributes['preview']) && $this->attributes['preview']) {
      Storage::delete(static::PATH['preview'] . $this->attributes['preview']);
    }

    $filename = str_random(10) . '.' . $img->extension();
    $img->storeAs(static::PATH['preview'], $filename);

    $this->preview = $filename;
    $this->save();
  }

  public function remove()
  {
    $this->removed = true;
    $this->save();
  }

  //======================================================================
  // Helpers
  //======================================================================
  
  public function isLiner()
  {
    return $this->type == 'liner';
  }

  public function isStorm()
  {
    return $this->type == 'storm';
  }

  public function isFinished()
  {
    $result = $this->result;

    if ($result->time) return true;

    if ($this->isLiner()) {
      return $result->cur_level > $this->levels_cnt;
    } else if ($this->isStorm()) {
      return $this->isEnougthAttempts();
    }
  }

  public function finish()
  {
    $result = $this->result;
    if ($result->time) return;
    $result->time = -now()->diffInSeconds($this->date_start, false);
    $result->save();
    # Отправляет всей команде по Socket протоколу сигнал на получение ерзультатов игты (!?)
  }

  public function isEnougthAttempts()
  {
    $levels = $this->levels()->get();
    $required = $levels->sum('required');
    $attempts = Attempt::where('team_id', Auth::user()->team_id)
      ->whereIn('level_id', $levels->pluck('id'))
      ->count();
    return $attempts >= $required;
  }
}