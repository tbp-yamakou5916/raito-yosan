<?php

namespace App\Libs\Calculator\Traits;

use App\Libs\Calculator\Edit1Calculator;

trait Edit3Productivity
{
  /**
   * 歩掛り
   *
   * @param bool $is_label
   * @return array
   */
  public function getProductivity(bool $is_label = true): array
  {
    $process_type = $this->process->process_type;
    // 	② 外注基本単価（円）
    $basic_price = (int) $this->free_form->price;
    //
    $process_item = null;

    // ------------------------------------
    // 施工数量
    // ------------------------------------
    switch ($process_type) {
      // 法面清掃工
      case 2:
        // 予算入力：法面清掃工：数量
        $process_item = $this->process->process_items->where('expense_item_id', 7)->first();
        $budget = $process_item ? $process_item->num : 0;
        // 累計出来高数量
        $progress = $this->process_terms->sum('num');
        // 施工完了数量
        $amount = $progress;
        break;
      // ラス張り工
      case 3:
      // 法枠組立工
      case 4:
      // 法枠吹付工
      case 5:
      // 枠内吹付工
      case 6:
        $expense_item_id = 0;
        if($process_type==3) {
          // 予算入力：ラス張り工：数量
          $expense_item_id = 12;
        } elseif ($process_type==4) {
          // 予算入力：フレーム組立：数量
          $expense_item_id = 22;
        } elseif ($process_type==5) {
          // 予算入力：モルタル吹付（法枠）：数量
          $expense_item_id = 35;
        }
        if(in_array($process_type, [3, 4, 5])) {
          $process_item = $this->process->process_items->where('expense_item_id', $expense_item_id)->first();
          $budget = $process_item ? $process_item->num : 0;
        } elseif($process_type==6) {
          // 予算入力：51 植生基材吹付：数量
          // 予算入力：52 モルタル吹付：数量
          // 予算入力：62 土のう等：数量
          $process_items = $this->process->process_items->whereIn('expense_item_id', [51, 52, 62]);
          $budget = $process_items->isNotEmpty() ? $process_items->sum('num') : 0;
        }
        // $budget × 作業進捗率
        $progress = $budget * $this->progress_rate;
        // 施工完了数量
        $amount = $this->finishedBaseValue();
        break;
      default:
        // ① 施工面積
        $budget = $this->free_form->area;
        // ① 施工面積 × 作業進捗率
        $progress = $budget * $this->progress_rate;
        // 施工完了数量
        $amount = $this->finishedBaseValue();
    }

    $num = [
      // 予算使用量
      'budget' => $budget,
      // 予算×進捗率
      'progress' => $progress,
      // 実績数量
      'amount' => $amount,
    ];

    // ------------------------------------
    // 作業日数
    // ------------------------------------
    switch ($process_type) {
      // 場内清掃工
      case 7:
        // 予算入力：外注費：数量
        $process_item = $this->process->process_items->where('expense_item_id', 54)->first();
        $budget = $process_item ? $process_item->num : 0;
        // 施工予定日数 × 作業進捗率
        $progress = $budget * $this->progress_rate;
        // 施工完了数量
        $amount = $this->process_terms->sum('real_day');
        break;
      default:
        // 施工予定日数
        $budget = $this->process->schedule_day;
        // 施工予定日数 × 作業進捗率
        $progress = $budget * $this->progress_rate;
        // 実績数量
        if($process_type == 2) {
          // 法面清掃工
          $amount = $this->process_terms->sum('real_day');
        } else {
          $amount = $this->finishedDay();
        }
    }
    // 実績数量 - 予算×進捗率
    $diff_rate = $amount - $progress;

    $day = [
      // 予算使用量
      'budget' => $budget,
      // 予算×進捗率
      'progress' => $progress,
      // 実績数量
      'amount' => $amount,
      // 計画との差異
      'diff_rate' => $diff_rate,
    ];

    // ------------------------------------
    // 作業人工
    // ------------------------------------
    switch ($process_type) {
      // 法面清掃工
      case 2:
      // 法枠吹付工
      case 5:
        // 予算 = 外注費：予算金額 / ② 外注基本単価（円）
        $budget = 0;
        // $process_item：上位で利用
        if($process_item) {
          // 外注費：予算金額
          $e1c = new Edit1Calculator();
          $e1c->setProcessItem($process_item->id, true);
          $res = $e1c->getResult();
          // 予算
          if($basic_price) {
            $price = !$res['result'] ? 0 : $res['result'];
            $budget = round($price / $basic_price);
          }
        }
        break;
      default:
        // （各外注費項目の予算金額÷外注基本単価）の合計
        $budget = $this->outsourcing_total['price'];
    }
    // 各外注費の［IN13］作業割合/日 ×［IN14］作業人数の合計 × 作業進捗率
    // 予算×進捗率 = 作業人工・予算使用量
    $progress = $budget * $this->progress_rate;
    // 実績数量 = 実績入力の人工（期間内施工人工）計
    $amount = $this->process_terms->sum('man_hour');
    // 実績数量 - 予算×進捗率
    $diff_rate = $amount - $progress;

    $man_hour = [
      // 予算使用量
      'budget' => $budget,
      // 予算×進捗率
      'progress' => $progress,
      // 実績数量
      'amount' => $amount,
      // 計画との差異
      'diff_rate' => $diff_rate,
    ];

    // ------------------------------------
    // 歩掛り
    // ------------------------------------
    switch ($process_type) {
      // 法面清掃工
      case 2:
        $budget = 0;
        if($man_hour['budget']) {
          $budget = $this->roundNum($num['budget'] / $man_hour['budget']);
        }
        $amount = 0;
        if($process_item) {
          // 範囲内面積(㎡)の合計値
          $area_within = $this->process_terms->sum('area_within');
          // 期間内施工人工の合計値
          $m_hour = $this->process_terms->sum('man_hour');
          // 期間内稼働日数の合計値
          $real_day = $this->process_terms->sum('real_day');
          // 累計歩掛り（㎡/人・日） = 範囲内面積(㎡)の合計値 ÷ （期間内施工人工の合計値 × 期間内稼働日数の合計値）
          if($m_hour * $real_day) {
            $amount = $this->roundNum($area_within / ($m_hour * $real_day));
          }
        }
        break;
      default:
        // A 予算数量 ＝ 施工数量：A 予算数量 / 作業人工：A 予算数量
        $budget = 0;
        if($man_hour['budget']) {
          $budget = $num['budget'] / $man_hour['budget'];
        }
        // C 実績数量 ＝ 施工数量：C 実績数量 / 作業人工：C 実績数量
        $amount = 0;
        if($man_hour['amount']) {
          $amount = $num['amount'] / $man_hour['amount'];
        }
    }
    // 実績数量 - 予算使用量
    $diff_rate = $amount - $budget;

    $step = [
      // 予算使用量
      'budget' => $budget,
      // 実績数量
      'amount' => $amount,
      // 計画との差異
      'diff_rate' => $diff_rate,
    ];

    // ------------------------------------
    // 1㎡当たりの作業単価
    // ------------------------------------
    switch ($process_type) {
      // 法面清掃工
      case 2:
        // A 予算数量 = ② 外注基本単価（円） / 歩掛り：A 予算数量
        $budget = 0;
        if($step['budget']) {
          $budget = round($basic_price / $step['budget']);
        }
        // 予算×進捗率
        $progress = $budget;

        // （作業人工：実績数量 * ② 外注基本単価（円））/ 施工数量：予算使用量
        $amount = 0;
        if($num['budget']) {
          $amount = round($man_hour['amount'] * $basic_price / $num['budget']);
        }
        break;
      default:
        // 作業人工：予算使用量 * ② 外注基本単価（円） / 施工数量：予算使用量
        $budget = 0;
        if($num['budget']) {
          $budget = round($man_hour['budget'] * $basic_price / $num['budget']);
        }
        // （作業人工：予算×進捗率 * ② 外注基本単価（円））/ 施工数量：予算×進捗率
        $progress = 0;
        if($num['progress']) {
          $progress = round(($man_hour['progress'] * $basic_price) / $num['progress']);
        }
        // （作業人工：実績数量 * ② 外注基本単価（円））/ 施工数量：予算使用量
        $amount = 0;
        if($num['budget']) {
          $amount = round($man_hour['amount'] * $basic_price / $num['budget']);
        }
    }
    // 実績数量 - 予算×進捗率
    $diff_rate = $amount - $progress;

    $price = [
      // 予算使用量
      'budget' => $budget,
      // 予算×進捗率
      'progress' => $progress,
      // 実績数量
      'amount' => $amount,
      // 計画との差異
      'diff_rate' => $diff_rate,
    ];

    $this->productivity = [
      // 施工数量
      'num' => $num,
      // 作業日数
      'day' => $day,
      // 作業人工
      'man_hour' => $man_hour,
      // 歩掛り
      'step' => $step,
      // 1㎡当たりの作業単価
      'price' => $price,
    ];

    if($is_label) {
      return [
        // 施工数量
        'num' => [
          'budget' => $this->getLabel('productivity_num', 'budget', 1),
          'progress' => $this->getLabel('productivity_num', 'progress', 1),
          'amount' => $this->getLabel('productivity_num', 'amount', 2),
        ],
        // 作業日数
        'day' => [
          'budget' => $this->getLabel('productivity_day', 'budget', 1),
          'progress' => $this->getLabel('productivity_day', 'progress', 1),
          'amount' => $this->getLabel('productivity_day', 'amount', 2),
          'diff_rate' => $this->getLabel('productivity_day', 'diff_rate', 4),
        ],
        // 作業人工
        'man_hour' => [
          'budget' => $this->getLabel('productivity_man_hour', 'budget', 1),
          'progress' => $this->getLabel('productivity_man_hour', 'progress', 1),
          'amount' => $this->getLabel('productivity_man_hour', 'amount', 2),
          'diff_rate' => $this->getLabel('productivity_man_hour', 'diff_rate', 4),
        ],
        // 歩掛り
        'step' => [
          'budget' => $this->getLabel('productivity_step', 'budget', 1),
          'amount' => $this->getLabel('productivity_step', 'amount', 2),
          'diff_rate' => $this->getLabel('productivity_step', 'diff_rate', 4),
        ],
        // 1㎡当たりの作業単価
        'price' => [
          'budget' => $this->getLabel('productivity_price', 'budget', 1),
          'progress' => $this->getLabel('productivity_price', 'progress', 1),
          'amount' => $this->getLabel('productivity_price', 'amount', 2),
          'diff_rate' => $this->getLabel('productivity_price', 'diff_rate', 4),
        ],
      ];
    } else {
      return $this->productivity;
    }
  }
}
