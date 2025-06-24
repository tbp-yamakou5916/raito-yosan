<?php

namespace App\Libs\Calculator;

use App\Models\Master\ExpenseCustomItem;
use App\Models\Master\ExpenseItem;
use App\Models\Process;
use App\Models\ProcessItem;
use Illuminate\Http\Request;

class Edit1Calculator {

  private int $default_weight;
  private mixed $free_form = null;
  private mixed $ff_category = null;
  private mixed $item = null;
  private array $ffc_result = [];
  private string $formula = '';
  private $num = 0;
  private $price = 0;
  private $rate = 0;
  private $rate2 = 0;
  private $quorum_rate = 0;
  private $result = '';
  private array $inputs = [];
  public function __construct() {
    // 1袋の重量(kg)
    $this->default_weight = 25;
  }

  /**
   * $requestによる設定
   * @param Request $request
   * @return void
   */
  public function setRequest(Request $request)
  {
    // カスタム費用項目フラグ
    $is_custom = (bool) $request->get('isCustom');
    // 項目ID
    $item_id = $request->get('itemId');
    // モデル取得
    if($is_custom) {
      $this->item = ExpenseCustomItem::find($item_id);
    } else {
      $this->item = ExpenseItem::find($item_id);
    }

    // 費用タイプ
    $cost_type = $request->get('type');
    // process_id
    $process_id = $request->get('processId');
    // フリーフォーム
    $this->free_form = Process::find($process_id)->free_form;
    // FFカテゴリ
    $this->ff_category = $this->free_form->ff_category ?? null;
    // フリーフォーム結果取得
    $ffc = new FreeFormCalculator($this->free_form);
    $this->ffc_result = $ffc->getResult();

    // 材料費
    if($cost_type=='material') {
      $weight = $request->get('weight');
      $this->num = $num = 0;
      if($request->get('num') > 0) {
        $this->num = $num = $request->get('num');
      }
      $price = $request->get('price');
      $rate = $request->get('rate');
      $this->inputs = [
        // 単位体積重量：セメント（袋）/ セメント（バラ）/ 砂（細骨材）
        1 => $weight ?? 0,
        // 数量
        2 => $num,
        // 単価
        3 => $price ?? 0,
        // ロス率
        4 => $rate ?? 0,
      ];
      $this->materialCalculate();
    }
    // 外注費
    if($cost_type=='outsourcing') {
      $this->num = $num = 0;
      if($request->get('num') > 0) {
        $this->num = $num = $request->get('num');
      }
      $popularity = $request->get('popularity');
      $rate2 = $request->get('rate2');
      $workerNum = $request->get('workerNum');
      $price = $request->get('price');
      $this->inputs = [
        // 数量
        11 => $num,
        // 想定人工
        12 => $popularity ?? 0,
        // 作業割合/日
        13 => $rate2 ?? 0,
        // 作業人数
        14 => $workerNum ?? 0,
        // 単価
        15 => $price ?? 0,
      ];
      $this->outsourcingCalculate();
    }
  }

  /**
   * mode2 / mode3：工程費用項目による数量の取得
   *
   * @param $process_item_id
   * @param int $cost_type
   * @param bool $need_result
   * @return void
   */
  public function setProcessItem($process_item_id, bool $need_result = false): void
  {
    // 工程費用項目
    $process_item = ProcessItem::find($process_item_id);
    // フリーフォーム
    $this->free_form = $process_item->process->free_form;
    // FFカテゴリ
    $this->ff_category = $this->free_form->ff_category ?? null;
    // フリーフォーム結果取得
    $ffc = new FreeFormCalculator($this->free_form);
    $this->ffc_result = $ffc->getResult();

    // モデル取得
    if($process_item->expense_custom_item_id) {
      $this->item = ExpenseCustomItem::find($process_item->expense_custom_item_id);
      $is_custom  = true;
    } else {
      $this->item = ExpenseItem::find($process_item->expense_item_id);
      $is_custom  = false;
    }

    $this->num = $num = $process_item->num ?? $this->item->default_num ?? 0;
    if($this->item->cost_type == 1) {
      // 単位体積重量：セメント（袋）/ セメント（バラ）/ 砂（細骨材）
      $this->inputs = [
        // 単位体積重量：セメント（袋）/ セメント（バラ）/ 砂（細骨材）
        1 => !$is_custom && $this->item->standard_is_num ? $process_item->standard_name : 0,
        // 数量
        2 => $num,
        // 単価
        3 => $process_item->price ?? 0,
        // ロス率
        4 => $process_item->rate ?? $this->item->default_rate ?? 0,
      ];
      // mode3
      if($need_result) {
        $this->materialCalculate();
      }
    } elseif($this->item->cost_type == 2) {
      $this->inputs = [
        // 数量
        11 => $process_item->num ?? 0,
        // 想定人工
        12 => $process_item->popularity ?? $this->item->default_popularity ?? 0,
        // 作業割合/日
        13 => $process_item->rate2 ?? $this->item->default_rate2 ?? 0,
        // 作業人数
        14 => $process_item->worker_num ?? 0,
        // 単価
        15 => $process_item->price ?? 0,
      ];
      // mode3
      if($need_result) {
        $this->outsourcingCalculate();
      }
    }
  }

  /**
   * mode2：入力値の取得
   * @return int
   */
  public function getNum(): int
  {
    return $this->num ?? 0;
  }

  /**
   * mode2：入力値の取得
   * @return array
   */
  public function getInputs(): array
  {
    return $this->inputs;
  }

  /**
   * 材料費
   * @return void
   */
  private function materialCalculate(): void
  {
    $this->price = $this->inputs[3];
    $this->result = $this->num * $this->inputs[3];
    $this->formula = $this->num . ' × ' . $this->inputs[3] . '= ' . $this->result;
  }

  /**
   * 外注費
   * @return void
   */
  private function outsourcingCalculate(): void
  {
    $this->price = $this->inputs[15];
    $this->result = $this->num * $this->price;
    $this->formula = $this->num . ' × ' . $this->price . ' = ' . $this->result;
  }

  /**
   * 結果取得
   * @return array
   */
  public function getResult(): array
  {
    $arr = [
      'num' => $this->num,
      'price' => $this->price,
      'rate' => $this->rate,
      'rate2' => $this->rate2,
      'formula' => $this->formula,
      'result' => $this->result,
    ];
    return $arr;
  }
}
