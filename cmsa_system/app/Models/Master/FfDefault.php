<?php

namespace App\Models\Master;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FfDefault extends Model
{
  use Common, FootPrint;

  protected $table = 'mst_ff_defaults';

  protected $fillable = [
    'ff_category_id',
    'expense_item_id',
    'standard_id',
    'created_by',
    'updated_by',
  ];

  // route/web.php
  public string $route = 'admin.master.ff_default.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */
  // FFカテゴリ
  public function ff_category(): BelongsTo
  {
    return $this->belongsTo(FfCategory::class);
  }
  // 費用項目
  public function expense_item(): BelongsTo
  {
    return $this->belongsTo(ExpenseItem::class);
  }
  // 規格
  public function standard(): BelongsTo
  {
    return $this->belongsTo(Standard::class);
  }

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
  /**
   * 費用項目名
   * expense_item_label
   */
  protected function expenseItemLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->expense_item->title ?? null
    );
  }
  /**
   * FFカテゴリ
   * ff_category_label
   */
  protected function ffCategoryLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->ff_category->longLabel ?? null
    );
  }
  /**
   * 規格名
   * standard_label
   */
  protected function standardLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->standard->title ?? null
    );
  }

  /*********************************************************
   * 関数
   */
}
