<?php

namespace App\Models;

use App\Models\Master\ExpenseCustomItem;
use App\Models\Master\ExpenseItem;
use App\Models\Master\Standard;
use App\Models\Master\Unit;
use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessItem extends Model
{
  use Common, FootPrint;

  protected $fillable = [
    'process_id',
    'expense_item_id',
    'expense_custom_item_id',
    'cost_type',
    'standard_id',
    'standard_name',
    'num',
    'price',
    'popularity',
    'rate',
    'rate2',
    'worker_num',
    'created_by',
    'updated_by',
    // ［材料］単位 ※expense_item_id：1 仮設材他（親綱・アンカー）のみで使用
    'unit_id',
  ];
  // 小数点第2位で取得
  protected function num(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => $value,
    );
  }



  // route/web.php
  public string $route = 'admin.process_item.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */
  // 工程
  public function process(): BelongsTo
  {
    return $this->belongsTo(Process::class);
  }
  // 費用項目
  public function expense_item(): BelongsTo
  {
    return $this->belongsTo(ExpenseItem::class);
  }
  // カスタム費用項目
  public function expense_custom_item(): BelongsTo
  {
    return $this->belongsTo(ExpenseCustomItem::class);
  }
  // 規格
  public function standard(): BelongsTo
  {
    return $this->belongsTo(Standard::class);
  }
  // 資材搬入
  public function deliveries(): HasMany
  {
    return $this->hasMany(Delivery::class);
  }
  // 期間内使用数量
  public function usages(): HasMany
  {
    return $this->hasMany(ProcessTermUsage::class);
  }
  // 単位
  public function unit(): BelongsTo
  {
    return $this->belongsTo(Unit::class);
  }

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
  /**
   * 搬入実績 合計
   * delivery_sum
   */
  protected function deliverySum(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->deliveries->sum('num') ?? 0,
    );
  }
  /**
   * num_html
   */
  protected function numHtml(): Attribute
  {
    return Attribute::make(
      get: function () {
        // expense_item_id：1 仮設材他（親綱・アンカー）の場合
        if($this->expense_item_id == 1) {
          return $this->unit->title ?? null;
        } else {
          return $this->num ? $this->num . $this->unitHtml : null;
        }
      }
    );
  }
  /**
   * process_label
   */
  protected function processLabel(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->process->process_label ?? null,
    );
  }
  /**
   * process_type
   */
  protected function processType(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->process->process_type ?? null,
    );
  }
  /**
   * standard_label
   */
  protected function standardLabel(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->standard->title ?? $this->standard_name,
    );
  }
  /**
   * title
   */
  protected function title(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->expense_custom_item->title ?? $this->expense_item->title ?? null,
    );
  }
  /**
   * unit_html
   */
  protected function unitHtml(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->expense_item->unitHtml ?? $this->expense_custom_item->unitHtml ?? null,
    );
  }
  /**
   * 使用実績 合計
   * usage_sum
   */
  protected function usageSum(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->usages->sum('usage') ?? 0,
    );
  }

  /*********************************************************
   * 関数
   */
}
