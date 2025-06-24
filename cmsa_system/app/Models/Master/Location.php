<?php

namespace App\Models\Master;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
  use SoftDeletes, Common, FootPrint;

  protected $table = 'mst_locations';

  protected $fillable = [
    'no',
    'title',
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
  public string $route = 'admin.master.location.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */

  /*********************************************************
   * 関数
   */
}
