<?php

namespace App\Models;

use App\Models\Master\ExpenseCustomItem;
use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
  use SoftDeletes, Common, FootPrint;

  protected $fillable = [
    'process_item_id',
    'delivered_at',
    'num',
    'created_by',
    'updated_by',
    'deleted_by',
    'deleted_at',
  ];

  protected function casts(): array
  {
    return [
      'delivered_at' => 'datetime',
      'deleted_at' => 'datetime',
    ];
  }

  // route/web.php
  public string $route = 'admin.delivery.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */
  // 工程費用項目
  public function process_item(): BelongsTo
  {
    return $this->belongsTo(ProcessItem::class);
  }


  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
  /**
   * delivered_at_label
   */
  protected function deliveredAtLabel(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->delivered_at ? $this->delivered_at->isoFormat('YYYY-MM-DD（ddd）') : null,
    );
  }
  /**
   * process_label
   */
  protected function processLabel(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->process_item->processLabel ?? null,
    );
  }
  /**
   * process_type
   */
  protected function processType(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->process_item->process_type ?? null,
    );
  }
  /**
   * process_title
   */
  protected function processTitle(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->process_item->processTitle ?? null,
    );
  }
  /**
   * standard_label
   */
  protected function standardLabel(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->process_item->standardLabel ?? null,
    );
  }
  /**
   * title
   */
  protected function title(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->process_item->title ?? null,
    );
  }
  /**
   * unit_html
   */
  protected function unitHtml(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->process_item->unitHtml ?? null,
    );
  }

  /*********************************************************
   * 関数
   */
}
