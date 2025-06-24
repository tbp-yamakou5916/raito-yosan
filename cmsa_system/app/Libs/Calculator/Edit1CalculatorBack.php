<?php

namespace App\Libs\Calculator;

use App\Models\Master\ExpenseCustomItem;
use App\Models\Master\ExpenseItem;
use App\Models\Process;
use App\Models\ProcessItem;
use Illuminate\Http\Request;

class Edit1CalculatorBack {

  private int $default_weight;
  private mixed $free_form = null;
  private mixed $ff_category = null;
  private mixed $item = null;
  private array $ffc_result = [];
  private string $formula = '';
  private $num = 0;
  private $price = 0;
  private $rate = 0;
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
      $num = $request->get('num');
      $price = $request->get('price');
      $rate = $request->get('rate');
      $this->inputs = [
        // 単位体積重量：セメント（袋）/ セメント（バラ）/ 砂（細骨材）
        1 => $weight ?? 0,
        // 数量
        2 => $num ?? 0,
        // 単価
        3 => $price ?? 0,
        // ロス率
        4 => $rate ?? 0,
      ];
      if(!$is_custom) {
        $this->materialCalculate();
      }
    }
    // 外注費
    if($cost_type=='outsourcing') {
      $num = $request->get('num');
      $popularity = $request->get('popularity');
      $rate2 = $request->get('rate2');
      $workerNum = $request->get('workerNum');
      $this->inputs = [
        // 数量
        11 => $num ?? 0,
        // 想定人工
        12 => $popularity ?? 0,
        // 作業割合/日
        13 => $rate2 ?? 0,
        // 作業人数
        14 => $workerNum ?? 0,
      ];
      if(!$is_custom) {
        $this->outsourcingCalculate();
      }
    }
  }

  /**
   * mode2 / mode3：工程費用項目による数量の取得
   *
   * @param $process_item_id
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

    if($this->item->cost_type == 1) {
      // 単位体積重量：セメント（袋）/ セメント（バラ）/ 砂（細骨材）
      $this->inputs = [
        // 単位体積重量：セメント（袋）/ セメント（バラ）/ 砂（細骨材）
        1 => !$is_custom && $this->item->standard_is_num ? $process_item->standard_name : 0,
        // 数量
        2 => $process_item->num ?? $this->item->default_num ?? 0,
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
      ];
      // mode3
      if($need_result) {
        $this->outsourcingCalculate();
      }
    }

    // mode2：数量のセット
    if(!$need_result) {
      if($is_custom) {
        $this->num = ceil($this->item->num * $this->item->rate);
      } else {
        $this->materialNum($this->item->id);
      }
    }
  }

  /**
   * mode2：数量の取得
   * @return int
   */
  public function getNum(): int
  {
    return $this->num ?? 0;
  }

  /**
   * 数量取得
   *
   * @param $expense_item_id
   * @return array
   */
  private function materialNum($expense_item_id): array
  {
    $is_loss_rate = true;
    $num_formula = null;
    // 計算数量
    switch ($expense_item_id) {
      case 8: // ワイヤーラス
        // 切上（① 面積（ラス張り面積）×［IN04］ロス率）
        $this->num = ceil($this->free_form->area * $this->inputs[4]);
        $num_formula = '切上（' . $this->free_form->area . ' × ' . $this->inputs[4] . '）';
        $is_loss_rate = false;
        break;
      case 9: // 主アンカーピン
        // 切上（① 面積（ラス張り面積）× 0.3 ×［IN04］ロス率）
        $this->num = ceil($this->free_form->area * 0.3 * $this->inputs[4]);
        $num_formula = '切上（' . $this->free_form->area . ' × 0.3 × ' . $this->inputs[4] . '）';
        $is_loss_rate = false;
        break;
      case 10: // 補助アンカーピン
        // 切上（① 面積（ラス張り面積）× 1.5 ×［IN04］ロス率）
        $this->num = ceil($this->free_form->area * 1.5 * $this->inputs[4]);
        $num_formula = '切上（' . $this->free_form->area . ' × 1.5 × ' . $this->inputs[4] . '）';
        $is_loss_rate = false;
        break;
      case 14: // フリーフォーム
        // 切上（⑬ 対象数量（=法枠延長） ×［IN04］ロス率）
        $this->num = ceil($this->ffc_result['quantity'] * $this->inputs[4]);
        $num_formula = '切上（' . $this->ffc_result['quantity'] . ' × ' . $this->inputs[4] . '）';
        $is_loss_rate = false;
        break;
      case 15: // 鉄筋
        // 切上（［FF09］鉄筋（kg） ÷［FF05］対象面積 × ① 面積（ラス張り面積） ×［IN04］ロス率）
      case 16: // 主アンカー
        // 切上（［FF07］主アンカー（本）÷［FF05］対象面積 × ① 面積（ラス張り面積） ×［IN04］ロス率）
      case 17: // 補助アンカー
        // 切上（［FF08］補助アンカー（本）÷［FF05］対象面積 × ① 面積（ラス張り面積） ×［IN04］ロス率）
      case 19:  // スターラップ
        // 切上（［FF11］スターラップ ÷［FF05］対象面積 × ① 面積（ラス張り面積） ×［IN04］ロス率）
        if($expense_item_id == 15) {
          $t_value = $this->ff_category->rebar; // 対象面積当たりの鉄筋（kg）使用量
        } elseif($expense_item_id == 16) {
          $t_value = $this->ff_category->main_anchor; // 対象面積当たりの主アンカー本数
        } elseif($expense_item_id == 17) {
          $t_value = $this->ff_category->sub_anchor; // 対象面積当たりの補助アンカー本数
        } elseif($expense_item_id == 19) {
          $t_value = $this->ff_category->stirrup; // スターラップ（組）
        } else {
          $t_value = 0;
        }
        $this->num = ceil($t_value / $this->ff_category->area * $this->free_form->area * $this->inputs[4]);
        $num_formula = '切上（' . $t_value . ' ÷ ' . $this->ff_category->area. ' × ' . $this->free_form->area . ' × ' . $this->inputs[4] . '）';
        $is_loss_rate = false;
        break;
      case 18:  // クリンプ金網
        // 切上（⑬ 対象数量（=法枠延長） ×［IN04］ロス率）
        $this->num = ceil($this->ffc_result['quantity'] * $this->inputs[4]);
        $num_formula = '切上（' . $this->ffc_result['quantity'] . ' × ' . $this->inputs[4] . '）';
        $is_loss_rate = false;
        break;
      case 27: // モルタル
        // 四捨五入（⑬ 対象数量（=法枠延長）× ② 法枠幅 × ② 法枠幅 ×［IN04］ロス率）
        // 小数点第2位で四捨五入
        $this->num = round($this->ffc_result['quantity'] * $this->ff_category->frame_width * $this->ff_category->frame_width * $this->inputs[4] * 10) / 10;
        $num_formula = '四捨五入(' . $this->ffc_result['quantity'] . ' × ' . $this->ff_category->frame_width . ' × ' . $this->ff_category->frame_width . ' × ' . $this->inputs[4] . ' × 10) ÷ 10';
        $is_loss_rate = false;
        break;
      case 29:  // セメント（袋）
        // 切上（［IN01］規格 × ⑬ 対象数量（=法枠延長）× ③ 枠内吹付厚さ ÷ 5 × 1袋の重量(kg) ÷ 1000 ×［IN04］ロス率）
        $this->num = ceil($this->inputs[1] * $this->ffc_result['quantity'] * $this->free_form->thickness / 5 * $this->default_weight / 1000 * $this->inputs[4]);
        $num_formula = '切上（' . $this->inputs[1] . ' × ' . $this->ffc_result['quantity'] . ' × ' . $this->free_form->thickness . ' ÷ 5 × ' . $this->default_weight . ' ÷ 1000 × ' . $this->inputs[4] . '）';
        $is_loss_rate = false;
        break;
      case 30:  // 砂（細骨材）
      case 45:  // モルタル
      case 47:  // セメント（袋）
      case 57:  // セメント（バラ）
      case 48:  // 砂（細骨材）
        // 切上（⑬ 対象数量（=法枠延長）×［IN04］ロス率）
        $this->num = ceil($this->ffc_result['quantity'] * $this->inputs[4]);
        $num_formula = '切上（' . $this->ffc_result['quantity'] . ' × ' . $this->inputs[4] . '）';
        $is_loss_rate = false;
        break;
      case 56:  // セメント（バラ）
        // 切上（［IN01］規格 × ⑬ 対象数量（=法枠延長）× ③ 枠内吹付厚さ ÷ 5 ÷ 1000 ×［IN04］ロス率）
        $this->num = ceil($this->inputs[1] * $this->ffc_result['quantity'] * $this->free_form->thickness / 5 / 1000 * $this->inputs[4]);
        $num_formula = '切上（' . $this->inputs[1] . ' × ' . $this->ffc_result['quantity'] . ' × ' . $this->free_form->thickness . ' ÷ 5 ÷ 1000 × ' . $this->inputs[4] . '）';
        break;
      case 32:  // 混和剤（防凍剤、減水剤等）
      case 59:  // 混和剤（防凍剤、減水剤等）
        // ① 面積（ラス張り面積）× ② 法枠幅 × ② 法枠幅
        $this->num = $this->free_form->area * $this->ff_category->frame_width * $this->ff_category->frame_width;
        $num_formula = $this->free_form->area . ' × ' . $this->ff_category->frame_width . ' × ' . $this->ff_category->frame_width;
        $is_loss_rate = false;
        break;
      default:
        $this->num = $this->inputs[2] ?? 0;
    }

    return [
      'is_loss_rate' => $is_loss_rate,
      'num_formula' => $num_formula
    ];
  }

  /**
   * 材料費
   * @return void
   */
  private function materialCalculate(): void
  {
    $res = $this->materialNum($this->item->id);

    // デフォルト設定
    $default_result = $this->num * $this->inputs[3];
    $default_formula = $res['num_formula'] . ' × ' . $this->inputs[3];
    if($res['is_loss_rate']) {
      $default_result *= $this->inputs[4];
      $default_formula .= ' × ' . $this->inputs[4];
    }

    switch ($this->item->id) {
      case 18:  // クリンプ金網
        // 計算数量 ×［IN03］単価 ÷ 5
        $this->result = ceil($default_result / 5);
        $this->formula = $default_formula . ' ÷ 5 = ' . $this->result;
        break;
      case 30:  // 砂（細骨材）
        // 計算数量 ×［IN03］単価 ×［IN04］ロス率 × ② 法枠幅 × ② 法枠幅 ×［IN01］規格 × 4 ÷ 5 ÷［IN01］規格
        $standard = $this->inputs[1] > 0 ? $this->inputs[1] : 1;
        $this->result = ceil($default_result * $this->ff_category->frame_width * $this->ff_category->frame_width * $standard * 4 / 5 / $standard);
        $this->formula = $default_formula . ' × ' . $this->ff_category->frame_width * $this->ff_category->frame_width . ' × ' . $standard . ' × 4 ÷ 5 ÷ ' . $standard;
        break;
      case 40:  // 緑化基盤材
        // ［IN02］数量 ×［IN03］単価 ×［IN04］ロス率 × ③ 枠内吹付厚さ × 2 ÷ 40(L/袋)
        $this->result = ceil($default_result * $this->free_form->thickness * 2 / 40);
        $this->formula = $default_formula . ' × ' . $this->free_form->thickness
          . ' × 2 ÷ 40 = ' . $this->result;
        break;
      case 44:  // スペーサー
        // ［IN03］単価 ×［IN04］ロス率 × ⑮ 1枠の枠内面積 × 1.8 × ⑭ 法枠数
        $this->result = ceil($this->inputs[3] * $this->inputs[4] * $this->ffc_result['oneFrameInnerArea'] * 1.8 * $this->ffc_result['frameNum']);
        $this->formula = $this->inputs[3]
          . ' × ' . $this->inputs[4]
          . ' × ' . $this->ffc_result['oneFrameInnerArea']
          . ' × 1.8 × ' . $this->ffc_result['frameNum']
          . ' = ' . $this->result;
        break;
      case 45:  // モルタル
        // ① 面積（ラス張り面積）×［IN03］単価 ×［IN04］ロス率 × ③ 枠内吹付厚さ
        $this->result = ceil($default_result * $this->free_form->thickness);
        $this->formula = $default_formula . ' × ' . $this->free_form->thickness
          . ' = ' . $this->result;
        break;
      case 47:  // セメント（袋）
        // ⑬ 対象数量（=法枠延長）×［IN03］単価 ×［IN04］ロス率 × ③ 枠内吹付厚さ ×［IN01］規格 ÷ 5 × 1袋の重量（kg) ÷ 1000
        $this->result = ceil($default_result * $this->free_form->thickness * $this->inputs[1] / 5 * $this->default_weight / 1000);
        $this->formula = $default_formula . ' × ' . $this->free_form->thickness. ' × ' . $this->inputs[1] . ' ÷ 5 × ' . $this->default_weight . ' ÷ 1000 = ' . $this->result;
        break;
      case 57:  // セメント（バラ）
        // ⑬ 対象数量（=法枠延長）×［IN03］単価 ×［IN04］ロス率 × ③ 枠内吹付厚さ ×［IN01］規格 ÷ 5 ÷ 1000
        $this->result = ceil($default_result * $this->free_form->thickness * $this->inputs[1] / 5 / 1000);
        $this->formula = $default_formula . ' × ' . $this->free_form->thickness. ' × ' . $this->inputs[1] . ' ÷ 5 ÷ 1000 = ' . $this->result;
        break;
      case 48:  // 砂（細骨材）
      // ⑬ 対象数量（=法枠延長）×［IN03］単価 ×［IN04］ロス率 × ③ 枠内吹付厚さ ×［IN01］規格 × 4 ÷ 5 ÷［IN01］規格
        $standard = $this->inputs[1] > 0 ? $this->inputs[1] : 1;
        $this->result = ceil($default_result * $this->free_form->thickness * $standard * 4 / 5 / $standard);
        $this->formula = $default_formula . ' × ' . $this->free_form->thickness. ' × ' . $standard . ' × 4 ÷ 5 ÷ ' . $standard . ' = ' . $this->result;
        break;
      default:
        // ［IN02］数量（又は上部「計算数量」） ×［IN03］単価 ×［IN04］ロス率
        $this->result = ceil($default_result);
        $this->formula = $default_formula . ' = ' . $this->result;
    }
  }

  /**
   * 外注費
   * @return void
   */
  private function outsourcingCalculate(): void
  {
    // ② 外注基本単価
    $basic_price = $this->free_form->price;
    // 計算数量
    switch ($this->item->id) {
      case 6: // 材料小運搬（単管パイプなどの運搬）
      case 7: // 法面清掃工
      case 12: // ラス張り工
      case 13: // 材料小運搬
      case 25: // 枠内養生工
      case 26: // 材料小運搬
      case 39: // コテ仕上げ
      case 54: // 清掃
        $this->num = $num_formula = $this->free_form->area; // ① 面積（ラス張り面積）
        break;
      case 21: // 芯出し
      case 22: // フレーム組立
        $this->num = $num_formula = $this->ffc_result['quantity']; // ⑬ 対象数量（=法枠延長）
        break;
      case 2: // 昇降階段設置・撤去
      case 3: // 法肩単管柵設置・撤去
      case 35: // モルタル吹付（法枠）
      case 36: // モルタル吹付（水切り）
      case 37: // モルタル吹付（間詰・不陸対応）
      case 38: // リバウンドロス清掃・集積・片付け
      case 51: // 植生基材吹付
      case 52: // モルタル吹付
      case 55: // 準備・撤去工
      case 4: // 親綱段取り・撤去
      case 5: // 起工測量・出来形測量
      case 34: // プラント設置・撤去
      case 53: // プラント組替
      default:
        $this->num = $num_formula = $this->inputs[11] ?? 0;
    }

    switch ($this->item->id) {
      case 2: // 昇降階段設置・撤去
      case 3: // 法肩単管柵設置・撤去
      case 6: // 材料小運搬（単管パイプなどの運搬）
      case 7: // 法面清掃工
      case 12: // ラス張り工
      case 13: // 材料小運搬
      case 21: // 芯出し
      case 22: // フレーム組立
      case 25: // 枠内養生工
      case 26: // 材料小運搬
      case 35: // モルタル吹付（法枠）
      case 36: // モルタル吹付（水切り）
      case 37: // モルタル吹付（間詰・不陸対応）
      case 38: // リバウンドロス清掃・集積・片付け
      case 39: // コテ仕上げ
      case 51: // 植生基材吹付
      case 52: // モルタル吹付
      case 54: // 清掃
        // ［IN11］数量 × ② 外注基本単価 ÷（［IN12］想定人工 ×［IN13］作業割合/日）
        $this->price = round($basic_price / ($this->inputs[12] * $this->inputs[13]));
        $this->result = ceil($this->num * $this->price);
        $this->formula = $num_formula
          . ' × ' . $basic_price
          . ' ÷（' . $this->inputs[12]
          . ' × ' . $this->inputs[13]
          . '）= ' . $this->result;
        // ［IN13］作業割合/日 ×［IN14］作業人数
        $this->quorum_rate = 0;
        break;
      case 55: // 準備・撤去工
      case 4: // 親綱段取り・撤去
      case 5: // 起工測量・出来形測量
      case 34: // プラント設置・撤去
      case 53: // プラント組替
        // ［IN11］数量 × ② 外注基本単価 ×［IN13］作業割合/日 ×［IN14］作業人数
        $this->price = round($basic_price * $this->inputs[13] * $this->inputs[14]);
        $this->result = ceil($this->num * $this->price);
        $this->formula = $num_formula
          . ' × ' . $basic_price
          . ' × ' . $this->inputs[13]
          . ' × ' . $this->inputs[14]
          . ' = ' . $this->result;
        // ［IN13］作業割合/日 ×［IN14］作業人数
        $this->quorum_rate = $this->inputs[13] * $this->inputs[14];
        break;
      default:
        $this->result = 0;
        $this->formula = '設定なし';
    }
  }

  /**
   * 結果取得
   * @return array
   */
  public function getResult(): array
  {
    return [
      'num' => $this->num,
      'price' => $this->price,
      'formula' => $this->formula,
      'result' => $this->result,
      // mode3：歩掛りで利用
      'quorum_rate' => $this->quorum_rate,
    ];
  }
}
