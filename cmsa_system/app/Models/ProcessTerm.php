<?php

namespace App\Models;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessTerm extends Model
{
  use Common, FootPrint;

  protected $fillable = [
    'free_form_id',
    'process_id',
    'real_day',
    'man_hour',
    'start',
    'end',
    'total_length',
    'length_within',
    'total_length_after',
    'total_area',
    'area_within',
    'total_area_after',
    'rate',
    'overall_rate',
    'intersection',
    'updated_by',
    'created_by',
  ];

  protected function casts(): array
  {
    return [
      'start' => 'date',
      'end' => 'date',
    ];
  }

  // route/web.php
  public string $route = 'admin.csv.';
  /*********************************************************
   * リレーション
   */
  // 工程
  public function process(): BelongsTo
  {
    return $this->belongsTo(Process::class);
  }
  // 登録済み施工期間
  public function process_terms(): HasMany
  {
    return $this->hasMany(self::class,  'process_id', 'process_id')->orderBy('start', 'asc');
  }
  // 期間内使用数量
  public function usages(): HasMany
  {
    return $this->hasMany(ProcessTermUsage::class);
  }
  // 施工期間工程費用項目（廃止可能性あり 2025/06/19）
  // ※「準備・撤去工」でのみ使用
  public function items(): HasMany
  {
    return $this->hasMany(ProcessTermItem::class);
  }
  // コメント
  public function comments(): HasMany
  {
    return $this->hasMany(ProcessTermComment::class)->orderBy('updated_at', 'desc');
  }

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
  /**
   * 範囲内面積(㎡)
   * area_within_label
   */
  protected function areaWithinLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->area_within ? $this->area_within . '㎡' : null
    );
  }
  /**
   * 施工日
   * construction_term_label
   */
  protected function constructionTermLabel(): Attribute
  {
    return Attribute::make(
      get: function() {
        $label = null;
        if($this->startLabel) {
          $label .= $this->startLabel;
        }
        if($label || $this->endLabel) {
          $label .= '～ ';
        }
        if($this->endLabel) {
          $label .= $this->endLabel;
        }
        return $label;
      }
    );
  }
  /**
   * 施工日 終了
   * end_label
   */
  protected function endLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->end ? $this->end->isoFormat('YYYY-MM-DD（ddd）') : null
    );
  }
  /**
   * フォーム用
   * end_value
   */
  protected function endValue(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->end ? $this->end->format('Y-m-d') : null
    );
  }
  /**
   * 範囲内延長(m)
   * length_within_label
   */
  protected function lengthWithinLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->length_within ? $this->length_within . 'm' : null
    );
  }
  /**
   * 全体進捗率(％)
   * overall_rate_label
   */
  protected function overallRateLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->overall_rate ? number_format($this->overall_rate) . '％' : null
    );
  }
  /**
   * 工程名
   * process_label
   */
  protected function processLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->processType ? __('array.process_type.params.' . $this->processType) : null
    );
  }
  /**
   * 工程名
   * process_type
   */
  protected function processType(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->process->process_type ?? null
    );
  }
  /**
   * 進捗率(％)
   * rate_label
   */
  protected function rateLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->rate ? number_format($this->rate) . '％' : null
    );
  }
  /**
   * 施工日 開始
   * start_label
   */
  protected function startLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->start ? $this->start->isoFormat('YYYY-MM-DD（ddd）') : null
    );
  }
  /**
   * フォーム用
   * start_value
   */
  protected function startValue(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->start ? $this->start->format('Y-m-d') : null
    );
  }
  /**
   * 施工終了総面積(㎡)
   * total_area_after_label
   */
  protected function totalAreaAfterLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->total_area_after ? $this->total_area_after . '㎡' : null
    );
  }
  /**
   * 総面積(㎡)
   * total_area_label
   */
  protected function totalAreaLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->total_area ? $this->total_area . '㎡' : null
    );
  }
  /**
   * 施工終了総延長(m)
   * total_length_after_label
   */
  protected function totalLengthAfterLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->total_length_after ? $this->total_length_after . 'm' : null
    );
  }
  /**
   * 総延長(m)
   * total_length_label
   */
  protected function totalLengthLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->total_length ? $this->total_length . 'm' : null
    );
  }

  /*********************************************************
   * 関数
   */
}
