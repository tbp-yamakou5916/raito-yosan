<?php

namespace App\Models\User;

use App\Models\Master\Location;
use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use App\Notifications\ResetPassword\UserNotification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use HasFactory, Notifiable, SoftDeletes, HasRoles;
  use FootPrint, Common;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'location_id',
    'name',
    'email',
    'password',
    'invalid',
    'created_by',
    'updated_by',
    'deleted_by',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
      'deleted_at' => 'datetime',
    ];
  }

  // route/web.php
  public string $route = 'admin.user.user.';
  /*********************************************************
   * リレーション
   */
  // 拠点
  public function location(): BelongsTo
  {
    return $this->belongsTo(Location::class);
  }

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
  /**
   * location_label
   */
  protected function locationLabel(): Attribute
  {
    return Attribute::make(
      get: function() {
        if($this->location_id == 9999) {
          return __('admin.user.user.all_location.label');
        } else {
          return  $this->location->title ?? null;
        }
      }
    );
  }

  /*********************************************************
   * 関数
   */
  /**
   * パスワードリセット用Notificationのオーバーライド
   * Override to send for password reset notification.
   *
   * @param [type] $token
   * @return void
   */
  public function sendPasswordResetNotification($token): void {
    $this->notify(new UserNotification($token));
  }
}
