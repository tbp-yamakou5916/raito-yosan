<?php

namespace App\Models;

use App\Models\Master\FfCategory;
use App\Models\Master\FfDefault;
use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreeForm extends Model
{
  use SoftDeletes, Common, FootPrint;

  protected $fillable = [
    'project_id',
    'title',
    'ff_category_id',
    // 施工情報
    'area', // ① 面積（㎡）
    'price',  // ② 外注基本単価（円）
    'thickness',  // ③ 枠内吹付厚さ（㎡）
    'quantity',  // ⑬ 対象数量（＝法枠延長）
    'frame_inner_area',  // ⑯ 枠内面積
    // 現場状況
    'straight_high_type',   // CD01：直高（市場単価該当項目）
    'distance_type',        // CD02：圧送距離（市場単価該当項目）
    'frame_width_type',     // CD03：法枠規格
    'roughness_type',       // CD04：地山の不陸
    'roughness_rate_type',  // CD05：全体面積に対する不陸の割合
  'gradient_type',          // CD06：斜面勾配
    'slope_length_type',    // CD07：法長(平均的な）
    'extension_type',       // CD08：施工延長（法尻）
    'area_type',            // CD09：面積
    'utilization_type',     // CD10：地山の硬軟・ハンマードリル使用率
    'is_stirrup',           // CD11：スターラップ
    'capabilities_type',    // CD12：協力業者の技量（係数）
    'transportation_type',  // CD13：運搬方法
    'small_transportation_type',  // CD14：材料小運搬（小段・人力)
    'is_trowel',            // CD15：コテ仕上げの有無
    'is_mortar',            // CD16：水切りモルタル（横梁）の有無
    'mixing_type',          // CD17：材料練混ぜ方法
    'is_movement',          // CD18：プラント場内移動

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
  public string $route = 'admin.free_form.';
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
  // CSV期間データ
  public function process_term(): HasOne
  {
    return $this->hasOne(ProcessTerm::class)
      ->orderBy('created_at', 'desc');
  }
  // プロセス
  public function processes(): HasMany
  {
    return $this->hasMany(Process::class);
  }
  // FFカテゴリ
  public function ff_category(): BelongsTo
  {
    return $this->belongsTo(FfCategory::class);
  }

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
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
   * project_title
   */
  protected function projectTitle(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->project->title ?? null
    );
  }

  /*********************************************************
   * 関数
   */
}
