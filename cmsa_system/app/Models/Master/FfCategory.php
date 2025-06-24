<?php

namespace App\Models\Master;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FfCategory extends Model
{
  use Common, FootPrint;

  protected $table = 'mst_ff_categories';

  protected $fillable = [
    'title',
    'width',
    'length',
    'frame_width',
    'area',
    'frame',
    'main_anchor',
    'sub_anchor',
    'rebar',
    'rebar_spec',
    'stirrup',
    'stirrup_spec',
    'sequence',
    'invalid',
    'created_by',
    'updated_by',
  ];

  // route/web.php
  public string $route = 'admin.master.ff_category.';
  public string $trans = 'admin.master.ff_category.';
  /*********************************************************
   * スコープ
   */

  /*********************************************************
   * リレーション
   */

  /*********************************************************
   * アクセサ／ミューテタ（ABC順）
   */
  /**
   * 対象面積
   * area_label
   */
  protected function areaLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->area ? number_format($this->area) . __($this->trans . 'area.unit') : null
    );
  }
  /**
   * フレーム材
   * frame_label
   */
  protected function frameLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->frame ? number_format($this->frame, 1) . __($this->trans . 'frame.unit') : null
    );
  }
  /**
   * フリーフォーム用配列
   * @return array
   */
  public static function frameWidthArray(): array
  {
    return self::where('invalid', 0)
      ->get()
      ->pluck('frame_width', 'id')->toArray();
  }
  /**
   * 法枠幅
   * frame_width_label
   */
  protected function frameWidthLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->frame_width ? number_format($this->frame_width, 2) . __($this->trans . 'frame_width.unit') : null
    );
  }
  /**
   * 縦枠寸法
   * length_label
   */
  protected function lengthLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->length ? number_format($this->length) . __($this->trans . 'length.unit') : null
    );
  }
  /**
   * フリーフォームタイプ
   * long_label
   */
  protected function longLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->title
        . '（横枠' . number_format($this->width)
        . __($this->trans . 'width.unit')
        . '✕縦枠' . number_format($this->length)
        . __($this->trans . 'length.unit') . '）'
    );
  }
  protected function shortLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => ($this->width / 10) . '✕' . ($this->length / 10)
    );
  }
  /**
   * 主アンカー
   * main_anchor_label
   */
  protected function mainAnchorLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->main_anchor ? number_format($this->main_anchor) . __($this->trans . 'main_anchor.unit') : null
    );
  }
  /**
   * 鉄筋
   * rebar_label
   */
  protected function rebarLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->rebar ? number_format($this->rebar, 1) . __($this->trans . 'rebar.unit') : null
    );
  }
  /**
   * スターラップ
   * stirrup_label
   */
  protected function stirrupLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->stirrup ? number_format($this->stirrup) . __($this->trans . 'stirrup.unit') : null
    );
  }
  /**
   * 補助アンカー
   * sub_anchor_label
   */
  protected function subAnchorLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->sub_anchor ? number_format($this->sub_anchor) . __($this->trans . 'sub_anchor.unit') : null
    );
  }
  /**
   * 横枠寸法
   * width_label
   */
  protected function widthLabel(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->width ? number_format($this->width) . __($this->trans . 'width.unit') : null
    );
  }

  /*********************************************************
   * 関数
   */
  /**
   * セレクト用配列作成
   * @return array
   */
  public static function selectArray(): array
  {
    return self::where('invalid', 0)
      ->get()
      ->pluck('longLabel', 'id')->toArray();
  }
  /**
   * セレクト用配列作成2
   * @return array
   */
  public static function selectArray2(): array
  {
    return self::where('invalid', 0)
      ->get()
      ->map(function ($ffCategory) {
        return [
          'id' => $ffCategory->id,
          'label' => $ffCategory->longLabel,
          'frame_width_type' => $ffCategory->frame_width_type,
        ];
      })
      ->toArray();
  }
}
