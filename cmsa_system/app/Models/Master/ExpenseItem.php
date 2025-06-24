<?php

namespace App\Models\Master;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseItem extends Model
{
  use SoftDeletes, Common, FootPrint;

  protected $table = 'mst_expense_items';

  protected $fillable = [
    'is_ff_default',
    'process_type',
    'cost_type',
    'title',
    'standard_is_num',
    'num_text',
    'num_is_output',
    'is_float',
    'disabled',
    'formula',
    'default_num',
    'default_rate',
    'default_rate2',
    'default_popularity',
    'is_multiple_unit',
    'unit_id',
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
  public string $route = 'admin.master.expense_item.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */
  // 費用項目
  public function standards(): HasMany
  {
    return $this->hasMany(Standard::class)
      ->where('invalid', 0)
      ->orderBy('sequence');
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
   * 費用タイプ
   * cost_type_label
   */
  protected function costTypeLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->cost_type ? __('array.cost_type.params.' . $this->cost_type) : null
    );
  }
  /**
   * 工程タイプ
   * process_type_label
   */
  protected function processTypeLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->process_type ? __('array.process_type.params.' . $this->process_type) : null
    );
  }
  /**
   * 単位マスター
   * unit_html
   */
  protected function unitHtml(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->unit->title ?? null
    );
  }
  /**
   * 想定人工 単位
   * unit_popularity
   */
  protected function unitPopularity(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->unit->is_popularity ? $this->unit->title . '/人･日' : '-'
    );
  }

  /*********************************************************
   * 関数
   */
  /**
   * 管理画面用セレクト配列
   *
   * @param bool $is_ff_default
   * @return array
   */
  public static function selectArray($is_ff_default = false): array
  {
    $query = self::where('invalid', 0);
    if($is_ff_default) {
      $query->where('is_ff_default', 1);
    }
    return $query->orderBy('sequence')
      ->get()
      ->map(function ($item) {
        $item->label = $item->processTypeLabel . '［' . $item->costTypeLabel .'］' . $item->title;
        return $item;
      })
      ->pluck('label', 'id')
      ->toArray();
  }
}
