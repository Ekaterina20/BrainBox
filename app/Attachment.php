<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
/*use \Storage;*/
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
  //======================================================================
  // Variables
  //======================================================================

  protected const TYPE = [
    'text',
    'img',
    'link',
    'video',
    'audio'
  ];

  private const PATH = '/uploads/a/';

  //======================================================================
  // Settings
  //======================================================================

  protected $fillable = [
    'level_id',
    /*'type',*/
    'value',
    /*'order',*/
  ];

  protected $hidden = [
    'level_id',
    'created_at',
    'updated_at',
    'order',
     'type',
  ];

  //======================================================================
  // Getters
  //======================================================================

  public function getTypeAttribute($type)
  {
    return self::TYPE[$type];
  }

  public function getValueAttribute($val)
  {
    if ($this->type == 'text' || $this->type == 'link') return $val;
    return asset(static::PATH . $val);
  }

  //======================================================================
  // Setters
  //======================================================================

  public function setTypeAttribute($type)
  {
    $this->attributes['type'] = array_flip(static::TYPE)[$type];
  }

  //======================================================================
  // Methods
  //======================================================================

  public static function add($data, $file)
  {
    $attach = new static;

    $attach->fill($data);
    $attach->setFile($file);

    static::where([
      ['level_id', $data['level_id']],
    /*  ['order', '>=', $data['order']]*/
    ])->increment('order');

    return $attach->save();
  }

  public function remove()
  {
    static::where([
      ['level_id', $this->level_id],
      ['order', '>', $this->order]
    ])->decrement('order');

    $this->removeFile();

    return $this->delete();
  }

  public function setFile($file)
  {
    if (!$file || $this->type == 'text' || $this->type == 'link') return;

    $this->removeFile();

    $filename = Str::random(10) . '.' . $file->extension();
    $file->storeAs(static::PATH, $filename);

    $this->value = $filename;
  }

  public function removeFile()
  {
    if (isset($this->attributes['value']) && $this->attributes['value']) {
      Storage::delete(static::PATH . $this->attributes['value']);
    }
  }
}
