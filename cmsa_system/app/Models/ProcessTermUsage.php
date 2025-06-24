<?php

namespace App\Models;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessTermUsage extends Model
{
  use Common, FootPrint;

  protected $fillable = [
    'process_term_id',
    'process_item_id',
    'usage',
    'man_hour',
    'updated_by',
    'created_by',
  ];

  /*********************************************************
   * リレーション
   */
  // 工程期間（CSV）
  public function process_term(): BelongsTo
  {
    return $this->belongsTo(ProcessTerm::class);
  }
  // 工程費用項目
  public function process_item(): BelongsTo
  {
    return $this->belongsTo(ProcessItem::class);
  }

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */

  /*********************************************************
   * 関数
   */
}
