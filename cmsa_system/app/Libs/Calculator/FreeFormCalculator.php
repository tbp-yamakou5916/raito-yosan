<?php

namespace App\Libs\Calculator;

use App\Models\Master\FfCategory;

class FreeFormCalculator {
  // ㉑ 法枠工面積
  private $frame_area;
  // ⑮ 1枠の枠内面積
  private $one_frame_inner_area;
  // ⑬ 対象数量
  private $quantity;
  // ⑯ 枠内面積
  private $frame_inner_area;
  // ⑭ 法枠数
  private $frame_num;
  // FFb 法枠幅
  private $frame_width;

  public function __construct($free_form) {

    if(!$free_form->ff_category_id) goto CALC_END;

    $ff_category = FfCategory::find($free_form->ff_category_id);

    if(!$ff_category) goto CALC_END;

    // ㉑ 法枠工面積
    $this->frame_area = $ff_category->width * $ff_category->length / 1000000;
    // ⑫ 法枠幅
    $this->frame_width = $ff_category->frame_width;
    // ⑮ 1枠の枠内面積
    //（［FF02：横枠寸法］÷ 1000 －［FF04：法枠幅］）×（［FF03：縦枠寸法］÷ 1000 －［FF04：法枠幅］）
    $this->one_frame_inner_area = round(($ff_category->width / 1000 - $this->frame_width) * ($ff_category->length / 1000 - $this->frame_width) * 1000) / 1000;

    if(!$free_form->area) goto CALC_END;

    // ⑬ 対象数量
    //［FF06：フレーム材］÷［FF05：対象面積］×［① 施工面積］
    $this->quantity = round($ff_category->frame / $ff_category->area * $free_form->area * 1000) / 1000;
    // ⑯ 枠内面積
    //［③ 面積］－［⑫ 対象数量］×［FF04：法枠幅］
    $this->frame_inner_area = round(($free_form->area - $this->quantity * $this->frame_width) * 1000) / 1000;
    // ⑭ 法枠数
    // ［⑯ 枠内面積］÷［⑮ 1枠の枠内面積］
    $this->frame_num = ceil($this->frame_inner_area / $this->one_frame_inner_area);

    CALC_END:
  }

  /**
   * 結果取得
   *
   * @return array
   */
  public function getResult(): array
  {
    return [
      // ㉑ 法枠工面積
      'frameArea' => $this->frame_area,
      // ⑫ 法枠幅
      'frameWidth' => $this->frame_width,
      // ⑬ 対象数量
      'quantity' => $this->quantity,
      // ⑭ 法枠数
      'frameNum' => $this->frame_num,
      // ⑮ 1枠の枠内面積
      'oneFrameInnerArea' => $this->one_frame_inner_area,
      // ⑯ 枠内面積
      'frameInnerArea' => $this->frame_inner_area,
    ];
  }
}
