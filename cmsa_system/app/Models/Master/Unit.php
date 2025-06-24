<?php

namespace App\Models\Master;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
  use SoftDeletes, Common, FootPrint;

  protected $table = 'mst_units';

  protected $fillable = [
    'title',
    'is_custom',
    'sequence',
    'invalid',
    'created_by',
    'updated_by',
    'deleted_by',
    'deleted_at',
  ];

  protected function casts(): array
  {
    return [
      'deleted_at' => 'datetime',
    ];
  }

  // route/web.php
  public string $route = 'admin.master.unit.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
  /**
   * 費用項目名
   * is_custom_label
   */
  protected function isCustomLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => __('array.is_custom.params.' . $this->is_custom)
    );
  }

  /*********************************************************
   * 関数
   */
}
