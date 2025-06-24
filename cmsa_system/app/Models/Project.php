<?php

namespace App\Models;

use App\Models\Master\Location;
use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
  use SoftDeletes, Common, FootPrint;

  protected $fillable = [
    'location_id',
    'code',
    'title',
    'user_id',
    'field_user1_id',
    'field_user2_id',
    'field_user3_id',
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
  public string $route = 'admin.project.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */
  // 現場工事長
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
  // 現場ユーザー1
  public function field_user1(): BelongsTo
  {
    return $this->belongsTo(User::class, 'field_user1_id');
  }
  // 現場ユーザー2
  public function field_user2(): BelongsTo
  {
    return $this->belongsTo(User::class, 'field_user2_id');
  }
  // 現場ユーザー3
  public function field_user3(): BelongsTo
  {
    return $this->belongsTo(User::class,  'field_user3_id');
  }
  // 拠点
  public function location(): BelongsTo
  {
    return $this->belongsTo(Location::class);
  }

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
  /**
   * 工事長
   * user_name
   */
  protected function userName(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->user->name ?? null
    );
  }
  /**
   * 工事長 拠点名
   * location_label
   */
  protected function locationLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->location->title ?? null
    );
  }

  /*********************************************************
   * 関数
   */
}
