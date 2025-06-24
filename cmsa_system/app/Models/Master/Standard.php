<?php

namespace App\Models\Master;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standard extends Model
{
  use SoftDeletes, Common, FootPrint;

  protected $table = 'mst_standards';

  protected $fillable = [
    'expense_item_id',
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
  public string $route = 'admin.master.standard.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */
  // 費用項目
  public function expense_item(): BelongsTo
  {
    return $this->belongsTo(ExpenseItem::class);
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
   * 工程タイプ
   * process_type_label
   */
  protected function processTypeLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->expense_item->processTypeLabel ?? null
    );
  }
  /**
   * 費用タイプ
   * cost_type_label
   */
  protected function costTypeLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->expense_item->costTypeLabel ?? null
    );
  }

  /*********************************************************
   * 関数
   */
}
