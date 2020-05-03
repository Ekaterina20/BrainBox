<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
  //======================================================================
  // Settings
  //======================================================================
  
  protected $fillable = [
    'level_id',
    'order',
    'code'
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
    'level_id'
  ];

  //======================================================================
  // Methods
  //======================================================================

  public static function add($data)
  {
    $answer = new static;
    $answer->fill($data);

    static::where([
      ['level_id', $data['level_id']],
      ['order', '>=', $data['order']]
    ])->increment('order');

    return $answer->save();
  }

  public function remove()
  {
    static::where([
      ['level_id', $this->level_id],
      ['order', '>', $this->order]
    ])->decrement('order');

    return $this->delete();
  }
}
