<?php

namespace App\Models;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessComment extends Model
{
  use Common, FootPrint;

  protected $fillable = [
    'process_id',
    'cost_type',
    'comment',
    'created_by',
    'updated_by',
  ];

  // route/web.php
  public string $route = 'admin.process_comment.';
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


  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */

  /*********************************************************
   * 関数
   */
}
