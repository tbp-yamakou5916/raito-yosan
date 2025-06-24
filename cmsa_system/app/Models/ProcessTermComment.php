<?php

namespace App\Models;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessTermComment extends Model
{
  use Common, FootPrint;

  protected $fillable = [
    'process_term_id',
    'cost_type',
    'condition_key',
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
  /**
   * 搬入実績 合計
   * condition_label
   */
  protected function conditionLabel(): Attribute
  {
    return Attribute::make(
      get: function () {
        if($this->condition_key) {
          $condition = collect(__('condition.' . $this->condition_key));
          return $condition['short'] ?? $condition['label'];
        } else {
          return null;
        }
      }
    );
  }

  /*********************************************************
   * 関数
   */
}
