<?php

namespace App\Models\Master;

use App\Models\Process;
use App\Models\Project;
use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCustomItem extends Model
{
  use SoftDeletes, Common, FootPrint;

  protected $table = 'mst_expense_custom_items';

  protected $fillable = [
    'project_id',
    'process_id',
    'cost_type',
    'title',
    'is_multiple_unit',
    'unit_id',
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
  public string $route = 'admin.process_custom_items.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */
  // プロジェクト
  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
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
}
