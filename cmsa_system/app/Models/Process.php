<?php

namespace App\Models;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Process extends Model
{
  use SoftDeletes, Common, FootPrint;

  protected $fillable = [
    'free_form_id',
    'process_type',
    'is_not_carried',
    'schedule_start',
    'schedule_end',
    'schedule_day',
    'invalid',
    'created_by',
    'updated_by',
    'deleted_by',
    'deleted_at',
  ];

  protected function casts(): array
  {
    return [
      'schedule_start' => 'date',
      'schedule_end' => 'date',
      'real_start' => 'date',
      'real_end' => 'date',
      'deleted_at' => 'datetime',
    ];
  }

  // route/web.php
  public string $route = 'admin.process.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */
  // フリーフォーム
  public function free_form(): BelongsTo
  {
    return $this->belongsTo(FreeForm::class);
  }
  // 費用項目
  public function process_items(): HasMany
  {
    return $this->hasMany(ProcessItem::class);
  }
  // 施工期間
  public function process_terms(): HasMany
  {
    return $this->hasMany(ProcessTerm::class);
  }
  // コメント
  public function comments(): HasMany
  {
    return $this->hasMany(ProcessComment::class);
  }

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
  /**
   * 施工期間
   * construction_period
   */
  protected function constructionPeriod(): Attribute
  {
    return Attribute::make(
      get: function() {
        $label = null;
        if($this->schedule_start) {
          $label .= $this->schedule_start->isoFormat('YYYY-MM-DD（ddd）');
        }
        if($label || $this->schedule_end) {
          $label .= '～';
        }
        if($this->schedule_end) {
          $label .= $this->schedule_end->isoFormat('YYYY-MM-DD（ddd）');
        }
        return $label;
      }
    );
  }
  /**
   * 施工期間2
   * construction_period
   */
  protected function constructionPeriod2(): Attribute
  {
    return Attribute::make(
      get: function() {
        $label = null;
        if($this->schedule_start) {
          $label .= $this->schedule_start->isoFormat('MM/DD（ddd）');
        }
        if($label || $this->schedule_end) {
          $label .= '～';
        }
        if($this->schedule_end) {
          $label .= $this->schedule_end->isoFormat('MM/DD（ddd）');
        }
        return $label;
      }
    );
  }
  /**
   * 工程名
   * process_label
   */
  protected function processLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->process_type ? __('array.process_type.params.' . $this->process_type) : null
    );
  }
  /**
   * 稼働日数
   * real_day_label
   */
  protected function realDayLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->real_day ? $this->real_day . '日' : '-'
    );
  }
  /**
   * 施工予定日数
   * schedule_day_label
   */
  protected function scheduleDayLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->schedule_day ? $this->schedule_day . '日' : '-'
    );
  }
  /**
   * フォーム用
   * schedule_end_value
   */
  protected function scheduleEndValue(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->schedule_end ? $this->schedule_end->format('Y-m-d') : null
    );
  }
  /**
   * フォーム用
   * schedule_start_value
   */
  protected function scheduleStartValue(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->schedule_start ? $this->schedule_start->format('Y-m-d') : null
    );
  }


  /*********************************************************
   * 関数
   */
}
