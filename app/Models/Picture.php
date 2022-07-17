<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Picture extends Model
{
    use HasFactory;

    /** 連番ではないので、incrementingをfalseにする */
    public $incrementing = false;

    /** プライマリーキーの型 */
    protected $keyType = 'string';

    /** JSONに追加する属性 */
    protected $appends = [
      'url',
    ];

    /** JSONに含める属性 */
    protected $visible = [
      'id', 'owner', 'url'
    ];

    /** 1ページあたりの項目数を制御する */
    protected $perPage = 15;

    /** IDの桁数 */
    const ID_LENGTH = 12;

    public function __construct(array $attributes = [])
    {
      parent::__construct($attributes);

      if(! Arr::get($this->attributes, 'id')) {
        $this->setId();
      }
    }

    /**
     * ランダムなID値をid属性に代入する
     * @return void
     */
    private function setId()
    {
      $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成する
     * @return string
     */
    private function getRandomId() {
      $characters = array_merge(
        range(0, 9), range('a', 'z'), range('A', 'Z'), ['-', '_']
      );

      $length = count($characters);

      $id = "";

      for ($i = 0; $i < self::ID_LENGTH; $i++) {
        $id .= $characters[random_int(0, $length - 1)];
      }

      return $id;
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
      return $this->belongsTo('App\Models\User', 'user_id', 'id', 'users');
    }

    /**
     * アクセサ - url
     * @return string
     */
    public function getUrlAttribute()
    {
      return Storage::cloud()->url($this->attributes['filename']);
    }
}
