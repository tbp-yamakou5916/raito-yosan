<?php

namespace App\Libs\Calculator;

use App\Libs\Calculator\Traits\Edit3Productivity;
use App\Models\Master\ExpenseCustomItem;
use App\Models\Master\ExpenseItem;
use App\Models\Process;

class Edit3Calculator {
  // 歩掛り
  use Edit3Productivity;

  private $free_form;
  private $process;
  private $process_type;
  private $process_terms;
  private $latest_process_term;

  /**
   * 基本情報
   */
  private $progress_rate;
  private $expense_items;

  /**
   * 材料費
   */
  private $unit;
  private $num_params;
  private $budget_params;
  private $budget_total = [
    'budget' => 0,
    'progress' => 0,
    'amount' => 0,
  ];
  /**
   * 外注費
   */
  private $out_num_params;
  private $out_budget_params;
  private $outsourcing_total = [
    'budget' => 0,
    'quorum_rate' => 0,
    'price' => 0,
    'in11' => 0,
    'in12' => 0,
  ];

  /**
   * 歩掛り
   */
  private $productivity = [];

  public function __construct($process_id) {
    $this->setVariables($process_id);
  }

  /**
   * 各値値の設定
   *
   * @param $process_id
   * @return void
   */
  private function setVariables($process_id): void
  {
    // 工程
    $this->process = $process = Process::find($process_id);
    $this->process_type = $process->process_type;
    // 工区
    $this->free_form = $this->process->free_form;
    // 施工期間
    $this->process_terms = $this->process->process_terms->sortByDesc('end');
    // 最新施工期間
    $this->latest_process_term = $this->process_terms->first();
    // 全体進捗率
    $this->progress_rate = 0;
    if($this->latest_process_term) {
      $this->progress_rate = $this->latest_process_term->overall_rate / 100;
    }

    // 費用項目
    $process_items = $process->process_items;

    $expense_items = collect();
    for($i=1; $i<=2; $i++) {
      if ($i == 1) {
        // 費用項目マスター
        $data = ExpenseItem::where('process_type', $this->process->process_type)
          ->where('invalid', 0)
          ->orderBy('sequence')
          ->get();
        $column_name = 'expense_item_id';
      } else {
        $project_id = $this->free_form->project_id;
        $data = ExpenseCustomItem::where('process_id', $process->id)
          ->where('project_id', $project_id)
          ->orderBy('created_at')
          ->get();
        $column_name = 'expense_custom_item_id';
      }

      // そもそも何も入力の無いデータを除去
      $data = $data->filter(function ($item) use($process_items, $column_name) {
        return $process_items->where($column_name, $item->id)->first();
      });

      // valuesの設定
      $data = $data->map(function ($item) use($process_items, $process, $column_name) {
        $process_item = $process_items->where($column_name, $item->id)->first();
        return $this->setEdit3Values($item, $process_item);
      });
      // 費用項目のマージ
      $expense_items = $expense_items->concat($data)->values();
    }
    $this->expense_items = $expense_items;
  }

  /**
   * 費用項目の設定
   *
   * @param $item
   * @param $process_item
   * @return mixed
   */
  private function setEdit3Values($item, $process_item): mixed
  {
    $this->setProcessItem($item, $process_item);

    if($item->cost_type==1) {
      $item->values = [
        // 材料ロス
        'num' => [
          // 予算使用量
          'num' => $this->getLabel('num', 'num', 1),
          // 予算×進捗率
          'progress' => $this->getLabel('num', 'progress', 1),
          // 計画ロス率
          'loss_rate' => $this->getLabel('num', 'loss_rate'),
          // 実績使用量
          'amount' => $this->getLabel('num', 'amount', 2),
          // 実質ロス率
          'los_rate2' => $this->getLabel('num', 'los_rate2', 2),
          // 計画との差異
          'diff_rate' => $this->getLabel('num', 'diff_rate', 4),
        ],
        // 材料費
        'budget' => [
          // 予算
          'budget' => $this->getLabel('budget', 'budget', 1),
          // 予算×進捗率
          'progress' => $this->getLabel('budget', 'progress', 1),
          // 実績使用額
          'amount' => $this->getLabel('budget', 'amount', 2),
          // 差額
          'difference' => $this->getLabel('budget', 'difference', 2),
          // 計画との差異
          'diff_rate' => $this->getLabel('budget', 'diff_rate', 4),
        ]
      ];
    } else {
      $item->values = [
        // 歩掛り
        'num' => [
          // 予算
          'budget' => $this->getLabel('out_num', 'budget', 1),
          // 予算×進捗率
          'progress' => $this->getLabel('out_num', 'progress', 1),
          // 実績使用額
          'amount' => $this->getLabel('out_num', 'amount', 2),
          // 計画との差異
          'diff_rate' => $this->getLabel('out_num', 'diff_rate', 4),
        ],
        // 外注費
        'budget' => [
        // 予算
        'budget' => $this->getLabel('out_budget', 'budget', 1),
        // 予算×進捗率
        'progress' => $this->getLabel('out_budget', 'progress', 1),
        // 実績使用額
        'amount' => $this->getLabel('out_budget', 'amount', 2),
        // 計画との差異
        'diff_rate' => $this->getLabel('out_budget', 'diff_rate', 4),
      ]
      ];
    }
    return $item;
  }

  /**
   * 基本情報 結果の取得
   *
   * @param bool $is_label
   * @return array
   */
  public function getBaseResult(bool $is_label = true): array
  {
    if($is_label) {
      return [
        // 施工予定数量
        'plan_area_label' => $this->planBaseValue($is_label),
        // 施工完了数量
        'finished_area_label' => $this->finishedBaseValue($is_label),
        // 施工予定日数
        'plan_day_label' => $this->planDay($is_label),
        // 稼働日数
        'finished_day_label' => $this->finishedDay($is_label),
        // 作業進捗率
        'progress_rate' => $this->progressRate(),
        'progress_rate_label' => $this->progressRate(true),
        // 外注基本単価
        'base_price_label' => $this->basePrice($is_label),
      ];
    } else {
      return [
        // 施工予定数量
        'plan_area' => $this->planBaseValue($is_label),
        // 施工完了数量
        'finished_area' => $this->finishedBaseValue($is_label),
        // 施工予定日数
        'plan_day' => $this->planDay($is_label),
        // 稼働日数
        'finished_day' => $this->finishedDay($is_label),
        // 作業進捗率
        'progress_rate' => $this->progressRate(),
        // 外注基本単価
        'base_price' => $this->basePrice($is_label),
      ];
    }
  }

  /**
   * 費用項目 結果の取得
   *
   * @return mixed
   */
  public function getExpenseItems(): mixed
  {
    return $this->expense_items;
  }

  /** ************************************************************
   * 基本情報
   *********************************************************** */

  /**
   * 予算数量
   *
   * @param bool $is_label
   * @return float|int|string
   */
  protected function planBaseValue(bool $is_label = false): float|int|string
  {
    $decimals = 2;
    switch($this->process_type) {
      // 準備・撤去工
      case 1:
      // 法面清掃工
      case 2:
      // 場内清掃工
      case 7:
        // 同一工程内の外注費予算合計 ではないか？
        $plan_value = 0;
        $e1c = new Edit1Calculator();
        foreach ($this->process->process_items as $item) {
          $e1c->setProcessItem($item->id, true);
          $res = $e1c->getResult();
          $plan_value += $res['result'];
        }
        $decimals = 0;
        $unit = '円';
        break;
      // 法枠組立工
      case 4:
      // 法枠吹付工
      case 5:
      // 総延長(m)
      $plan_value = $this->latest_process_term->total_length;
      $unit = 'm';
        break;
      default:
        // 総面積(㎡)
        $plan_value = $this->latest_process_term->total_area ?? 0;
        $unit = 'm&sup2;';

    }
    if($is_label) {
      return $plan_value ? number_format($plan_value, $decimals) . $unit : '-';
    } else {
      return $plan_value;
    }
  }

  /**
   * 実績数量
   *
   * @param bool $is_label
   * @return float|int|string
   */
  public function finishedBaseValue(bool $is_label = false): float|int|string
  {
    $decimals = 2;
    switch($this->process_type) {
      // 準備・撤去工
      case 1:
        // 法面清掃工
      case 2:
        // 場内清掃工
      case 7:
        // 同一工程内の外注費予算合計 ではないか？
        // （項目の累計出来高数量×項目の単価）の合計
        $finished_value = 0;
        foreach ($this->process_terms as $term) {
          foreach ($term->usages as $usage) {
            $process_item = $usage->process_item;
            $finished_value += $usage->usage * $process_item->price;
          }
        }
        $decimals = 0;
        $unit = '円';
        break;
      // 法枠組立工
      case 4:
        // 法枠吹付工
      case 5:
        // 総延長(m)
        // 施工終了総延長(m)
        $finished_value = $this->latest_process_term->total_length_after;
        $unit = 'm';
        break;
      default:
        // 施工終了総面積(㎡)
        $finished_value = $this->latest_process_term->total_area_after ?? 0;
        $unit = 'm&sup2;';
    }
    if($is_label) {
      return $finished_value ? number_format($finished_value, $decimals) . $unit : '-';
    } else {
      return $finished_value;
    }
  }

  /**
   * 施工予定日数
   *
   * @param bool $is_label
   * @return float|int|string
   */
  public function planDay(bool $is_label = false): float|int|string
  {
    // 施工予定日数
    $plan_day = $this->process->schedule_day;
    if($is_label) {
      return $plan_day ? number_format($plan_day, 2) . '日' : '-';
    } else {
      return $plan_day ?? 0;
    }
  }

  /**
   * 稼働日数
   *
   * @param bool $is_label
   * @return float|int|string
   */
  public function finishedDay(bool $is_label = false): float|int|string
  {
    $finished_day = $this->process_terms->sum('real_day');
    if($is_label) {
      return $finished_day ? number_format($finished_day, 2) . '日' : '-';
    } else {
      return $finished_day;
    }
  }

  /**
   * 作業進捗率ラベル
   *
   * @param bool $is_label
   * @return string
   */
  public function progressRate(bool $is_label = false): string
  {
    $progress_rate = $this->progress_rate;
    if(in_array($this->process_type, [1, 2, 7])) {
      $material_result = $this->getMaterialResult(false);
      // 下記を行う前に実行が必要
      $this->getProductivity();
      $outsourcing_result = $this->getOutsourcingResult(false);
      // 予算数量
      $plan = $material_result['budget'] + $outsourcing_result['budget'];
      // 実績数量
      $real = $material_result['progress'] + $outsourcing_result['progress'];
      // 実績数量 ÷ 予算数量
      if($plan) {
        $progress_rate = round($real / $plan, 2);
      }
    }

    if($is_label) {
      return $progress_rate ? number_format($progress_rate * 100) . '%' : '-';
    } else {
      return $progress_rate * 100;
    }
  }
  /**
   * 外注基本単価
   *
   * @param bool $is_label
   * @return float|int|string
   */
  protected function basePrice(bool $is_label = false): float|int|string
  {
    $base_price = $this->free_form->price;
    if($is_label) {
      return $base_price ? number_format($base_price) . '円' : '-';
    } else {
      return $base_price;
    }
  }

  /** ************************************************************
   * 材料費
   *********************************************************** */

  /**
   * 工程費用項目の設定
   *
   * @param $item
   * @param $process_item
   */
  public function setProcessItem($item, $process_item): void
  {
    // 単位
    $this->unit = $item->unitHtml;

    // 数量取得
    $e1c = new Edit1Calculator();
    $e1c->setProcessItem($process_item->id, true);

    // 結果の設定
    $result = $e1c->getResult();

    // 使用数量（累積）
    $usage_sum = $process_item->usageSum;

    if($process_item->cost_type == 1) {
      // 材料ロス
      // A 予算数量
      $num = $result['num'];
      // B 予算数量×進捗率 = A 予算数量 × 作業情報の「進捗率」
      $progress = $num * $this->progress_rate;
      // C 予算ロス率 = 予算入力の［IN04］ロス率
      $loss_rate = $process_item->rate;
      // D 実績数量 = 資材搬入登録の在庫管理の「使用数量（累積）」
      $amount = $usage_sum;
      // E 実績ロス率 = D 実績数量 ÷（B 予算数量×進捗率 ÷ C 予算ロス率）
      $los_rate2 = 0;
      if($progress * $loss_rate) {
        $los_rate2 = $amount / ($progress / $loss_rate);
      }
      // E-C 予算との差異 = E 実績ロス率 - C 予算ロス率
      $diff_rate = $los_rate2 - $loss_rate;

      $this->num_params = [
        'num' => $num,
        'progress' => $progress,
        'loss_rate' => $loss_rate,
        'amount' => $amount,
        'los_rate2' => $los_rate2,
        'diff_rate' => $diff_rate,
      ];

      // 材料費
      // A 予算金額
      $budget = $result['result'];
      // B 予算金額×進捗率 = A 予算金額 × 基本情報の「進捗率」
      $progress = $budget * $this->progress_rate;
      // C 実績金額：材料ロス「D 実績数量」× 予算入力の「単価（In03）」
      $amount = $amount * $result['price'];
      // D 予算との差異 = C 実績金額 - B 予算金額×進捗率
      $difference = $amount - $progress;
      // 計画との差異 = D 予算との差異 ÷ B 予算金額×進捗率
      $diff_rate = 0;
      if($progress) {
        $diff_rate = round($difference / $progress * 1000) / 10;
      }

      $this->budget_params = [
        'budget' => $budget,
        'progress' => $progress,
        'amount' => $amount,
        'difference' => $difference,
        'diff_rate' => $diff_rate,
      ];

      // 材料費合計 A 予算金額
      $this->budget_total['budget'] += $budget;
      // 材料費合計 B 予算金額×進捗率
      $this->budget_total['progress'] += $progress;
      // 材料費合計 C 実績金額
      $this->budget_total['amount'] += $amount;
    } else {
      // 歩掛り
      // 人工（予算）= 予算金額 / ②外注基本単価
      $base_price = $this->basePrice();
      $man_hour = round($result['result'] / $base_price, 2);
      // A 予算数量
      $budget = 0;
      if($man_hour) {
        $budget = $result['num'] / $man_hour;
      }
      // B 予算数量×進捗率 = A 予算金額 × 基本情報の「進捗率」
      $progress = $budget;
      // C 実績数量：累計歩掛り = 実績入力：期間内出来高数量 / 実績入力：実施人工 の合計
      $amount = 0;
      $total_usage = 0;
      $total_man_hour = 0;
      foreach ($this->process_terms as $term) {
        $items = $term->usages->where('process_item_id', $process_item->id);
        foreach ($items as $item) {
          if($item->man_hour > 0) {
            $amount += round($item->usage / $item->man_hour, 2);
          }
          $total_usage += $item->usage;
          $total_man_hour += $item->man_hour;
        }
      }
      // D 予算との差異 = C 実績金額 - B 予算金額×進捗率
      $diff_rate = $amount - $progress;

      $this->out_num_params = [
        'budget' => $budget,
        'progress' => $progress,
        'amount' => $amount,
        'diff_rate' => $diff_rate,
        'unitHtml' => $process_item->unitHtml,
      ];

      // 外注費
      // A 予算数量
      $budget = $result['result'];
      // 進捗率 = 累計出来高数量 / 予算入力：予算数量
      $rate = 0;
      if($result['num'] > 0) {
        $rate = $total_usage / $result['num'];
      }
      // B 予算数量×進捗率 = A 予算数量 × 進捗率
      $progress = $budget * $rate;
      // C 実績数量 = 実施人工の合計 × ②外注基本単価
      $amount = $total_man_hour * $base_price;
      // D 予算との差異 = D 実績数量 ÷（B 予算数量×進捗率 ÷ C 予算ロス率）
      $diff_rate = $amount - $progress;

      $this->out_budget_params = [
        'budget' => $budget,
        'progress' => $progress,
        'amount' => $amount,
        'diff_rate' => $diff_rate,
      ];

      // 着地見込み
      // 予算 = 予算入力の「［OUT12］予算」の合計値
      $this->outsourcing_total['budget'] += $result['result'];
      // 作業人工 予算使用量
      // （各外注費項目の予算金額 ÷ 外注基本単価）の合計
      if($this->free_form->price) {
        $this->outsourcing_total['price'] += $result['result'] / $this->free_form->price;
      }
    }
  }

  /**
   * 材料費合計
   *
   * @param bool $is_label
   * @return array
   */
  public function getMaterialResult(bool $is_label = true): array
  {
    $amount2 = 0;
    if($this->progress_rate) {
      // 材料費合計 C 実績金額 / 基本情報の「進捗率」
      $amount2 = $this->budget_total['amount'] / $this->progress_rate;
    }
    $this->budget_total['amount2'] = $amount2;

    // 計画との差異
    $this->budget_total['diff'] = $this->budget_total['amount'] - $this->budget_total['progress'];
    $this->budget_total['diff2'] = $amount2 - $this->budget_total['budget'];

    if($is_label) {
      return [
        // 材料費合計 予算
        'budget' => $this->getLabel('material_total', 'budget', 1),
        // 材料費合計 予算×進捗率
        'progress' => $this->getLabel('material_total', 'progress', 1),
        // 材料費合計 実績使用額
        'amount' => $this->getLabel('material_total', 'amount', 1),
        'amount2' => $this->getLabel('material_total', 'amount2', 1),
        // 材料費合計 計画との差異
        'diff' => $this->getLabel('material_total', 'diff', 4),
        'diff2' => $this->getLabel('material_total', 'diff2', 4),
        // 材料費合計 最終結果
        'result' => $this->getLabel('material_total', 'diff2', 6),
      ];
    } else {
      return $this->budget_total;
    }
  }

  /**
   * 外注費合計
   *
   * @param bool $is_label
   * @return array
   */
  public function getOutsourcingResult(bool $is_label = true): array
  {
    // 外注費：A 予算金額
    $budget = $this->outsourcing_total['budget'];

    // 外注費：B 予算金額×進捗率 = 外注費：A 予算金額× 基本情報の「進捗率」
    $this->outsourcing_total['progress'] = $progress = $budget * $this->progress_rate;

    // 外注費：C 実績金額 = 歩掛り：作業人工：C 実績数量 × ② 外注基本単価
    $basic_price = (int) $this->free_form->price;
    $amount = $this->productivity['man_hour']['amount'] * $basic_price;
    $this->outsourcing_total['amount'] = $amount;

    // 着地見込み：C 実績金額 = 外注費：C 実績金額 / 作業情報：作業進捗率
    $amount2 = 0;
    if($this->progress_rate) {
      $amount2 = $amount / $this->progress_rate;
    }
    $this->outsourcing_total['amount2'] = $amount2;

    // 外注費：計画との差異
    $this->outsourcing_total['diff'] = $amount - $progress;

    // 着地見込み：計画との差異
    $this->outsourcing_total['diff2'] = $amount2 - $budget;

    if($is_label) {
      return [
        // 外注費：予算
        'budget' => $this->getLabel('outsourcing_total', 'budget', 1),
        // 着地見込み：予算×進捗率
        'progress' => $this->getLabel('outsourcing_total', 'progress', 1),
        // 外注費：実績
        'amount' => $this->getLabel('outsourcing_total', 'amount', 2),
        // 着地見込み：実績
        'amount2' => $this->getLabel('outsourcing_total', 'amount2', 2),
        // 外注費：計画との差異
        'diff' => $this->getLabel('outsourcing_total', 'diff', 4),
        // 着地見込み：計画との差異
        'diff2' => $this->getLabel('outsourcing_total', 'diff2', 4),
        // 想定追加原価
        'result' => $this->getLabel('outsourcing_total', 'diff2', 6),
      ];
    } else {
      return $this->outsourcing_total;
    }
  }

  /**
   * @param $type
   * @param $name
   * @param int $format
   * @return mixed|string
   */
  public function getLabel($type, $name, int $format = 0): mixed
  {
    $decimals = 0;
    $is_productivity = false;
    switch($type) {
      // 材料ロス
      case 'num':
        $arr = $this->num_params;
        $unit = $this->unit;
        if(in_array($name, ['loss_rate', 'los_rate2', 'diff_rate'])) {
          $decimals = 3;
        }
        if(in_array($name, ['loss_rate', 'los_rate2'])) {
          $unit = null;
        }
        break;
      // 材料費
      case 'budget':
        $arr = $this->budget_params;
        $unit = '円';
        break;
      // 外注費 歩掛り
      case 'out_num':
        $arr = $this->out_num_params;
        $unit = $arr['unitHtml'] . '/人･日';
        $decimals = 2;
        break;
      // 外注費
      case 'out_budget':
        $arr = $this->out_budget_params;
        $unit = '円';
        break;
      // 材料費合計
      case 'material_total':
        $arr = $this->budget_total;
        $unit = '円';
        break;
      // 外注費合計
      case 'outsourcing_total':
        $arr = $this->outsourcing_total;
        $unit = '円';
        break;
      // 歩掛り 作業数量
      case 'productivity_num':
        $is_productivity = true;
        $arr = $this->productivity['num'];
        if(in_array($this->process_type, [4, 5])) {
          $unit = 'm';
        } else {
          $unit = 'm&sup2;';
        }
        $decimals = 2;
        break;
      // 歩掛り 作業日数
      case 'productivity_day':
        $is_productivity = true;
        $arr = $this->productivity['day'];
        $unit = '日';
        $decimals = 2;
        break;
      // 歩掛り 作業人工
      case 'productivity_man_hour':
        $is_productivity = true;
        $arr = $this->productivity['man_hour'];
        $unit = '人工';
        $decimals = 2;
        break;
      // 歩掛り 歩掛り
      case 'productivity_step':
        $is_productivity = true;
        $arr = $this->productivity['step'];
        if(in_array($this->process_type, [4, 5])) {
          $unit = 'm/人･日';
        } else {
          $unit = 'm&sup2;/人･日';
        }
        $decimals = 2;
        break;
      // 歩掛り 1㎡当たりの作業単価
      case 'productivity_price':
        $is_productivity = true;
        $arr = $this->productivity['price'];
        $unit = '円';
        break;
      default:
        $arr = [];
        $unit = null;
    }

    $arr[$name] = $arr[$name] ?? 0;

    $class = null;
    if($type == 'productivity_step' && $name == 'diff_rate') {
      if ($arr[$name] > 0) {
        $class = ' class="text-primary"';
      } elseif ($arr[$name] < 0) {
        $class = ' class="text-danger"';
      }
    } elseif($name == 'diff_rate' || in_array($type, ['material_total', 'outsourcing_total']) && in_array($name, ['diff', 'diff2'])) {
      if($arr[$name] > 0) {
        $class = ' class="text-danger"';
      } elseif($arr[$name] < 0) {
        $class = ' class="text-primary"';
      }
    }

    switch ($format) {
      // 材料ロス 予算使用量：num
      // 材料ロス 予算×進捗率：progress
      // 材料費 予算：budget
      // 材料費 予算×進捗率：progress
      // 材料費合計 予算：budget
      // 材料費合計 予算×進捗率：progress
      case 1:
        return number_format($arr[$name], $decimals) . ' ' . $unit;

      // 材料ロス 実績使用額：amount
      // 材料ロス 実質ロス率：los_rate2
      // 材料費 実績使用額：amount
      // 材料費 差額：difference
      // 材料費合計 実績使用額：amount
      // 材料費合計 実績使用額：amount2
      // 外注費合計 実績：amount
      // 外注費合計 実績：amount2
      case 2:
        return '<b' . $class . '>' . number_format($arr[$name], $decimals) . '</b> ' . $unit;

      // 材料費 計画との差異：diff_rate
      // 材料費合計 計画との差異：diff,diff2
      // 外注費合計 計画との差異：diff,diff2
      case 4:
        $decimals = 2;
        if($type != 'outsourcing') {
          if(in_array($type, ['out_budget', 'material_total', 'outsourcing_total'])) {
            $unit = '円';
          } elseif($type=='num') {
            $unit = '';
          } elseif(!$is_productivity) {
            $unit = '%';
          }
        }
        if($unit == '円') {
          $decimals = 0;
        }

        $sign = null;
        if($arr[$name] > 0) {
          $sign = '+';
        } elseif(!$arr[$name]) {
          $sign = '±';
        }
        return '<b' . $class . '>' . $sign . number_format($arr[$name], $decimals) . '</b> ' . $unit;

      // 材料費合計 最終結果：diff2
      case 6:
        return number_format($arr[$name], $decimals);

      // 材料ロス 計画ロス率
      default:
        return $arr[$name];
    }
  }

  /**
   * 小数点以下 四捨五入
   *
   * @param $num
   * @return float|int
   */
  private function roundNum($num): float|int
  {
    return round($num * 1000) / 1000;
  }
}
