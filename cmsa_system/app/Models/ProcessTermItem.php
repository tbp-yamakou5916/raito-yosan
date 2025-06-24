<?php

namespace App\Models;

use App\Models\Master\ExpenseItem;
use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessTermItem extends Model
{
  // ******************************
  // 未利用 2025/6/24
  // （廃止可能性あり 2025/06/19）
  // ******************************

  use Common, FootPrint;

  protected $fillable = [
    'process_term_id',
    'expense_item_id',
    'man_hour',
    'num',
    'rate',
    'overall_rate',
    'created_by',
    'updated_by',
  ];

  /*********************************************************
   * リレーション
   */
  // 工程期間（CSV）
  public function process_term(): BelongsTo
  {
    return $this->belongsTo(ProcessTerm::class);
  }
  // 費用項目マスター
  public function expense_item(): BelongsTo
  {
    return $this->belongsTo(ExpenseItem::class);
  }

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */

  /*********************************************************
   * 関数
   */
}
