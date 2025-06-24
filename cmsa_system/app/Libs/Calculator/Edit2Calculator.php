<?php

namespace App\Libs\Calculator;

use App\Models\ProcessItem;
use App\Models\ProcessTerm;

class Edit2Calculator {
  /**
   * 予算入力値
   */
  private $e1cInputs;
  private $e1cResult;
  private $num;
  /**
   * 工程
   */
  private $process_type;
  private $process_term;
  /**
   *
   */
  private $process_term_usages;
  private $process_terms;
  private $pre_process_terms;
  private $pre_total_man_hour;
  private $pre_total_real_day;
  private $pre_total_area_within;
  private $pre_total_length_within;
  private $process_item_id;
  public function __construct($process_term_id)
  {
    // 施工期間
    $this->process_term =  ProcessTerm::find($process_term_id);

    // 工程
    $process = $this->process_term->process;
    $this->process_type = $process->process_type;

    // 施工期間内使用数量
    $this->process_term_usages = $this->process_term->usages;

    // 登録済み工程期間
    $this->process_terms = $this->process_term->process_terms;

    // 指定施工期間を含む以前の施工期間
    $this->pre_process_terms = $this->process_terms->where('end', '<=', $this->process_term->end);

    // 指定施工期間を含む以前の累計した期間内 範囲内面積(㎡)
    $this->pre_total_area_within = $this->pre_process_terms->sum('area_within');
    // 指定施工期間を含む以前の累計した期間内 範囲内延長(m)
    $this->pre_total_length_within = $this->pre_process_terms->sum('length_within');

    // 指定施工期間以前の施工期間
    $this->pre_process_terms = $this->process_terms->where('end', '<', $this->process_term->end);

    // 当施工期間期間を含めない以前の施工人工の累計
    $this->pre_total_man_hour = $this->pre_process_terms->sum('man_hour');
    // 当施工期間期間を含めない以前の稼働日数の累計
    $this->pre_total_real_day = $this->pre_process_terms->sum('real_day');
  }

  /**
   * 工程費用項目の設定
   *
   * @param $process_item_id
   */
  public function setProcessItem($process_item_id): void
  {
    $this->process_item_id = $process_item_id;

    // 数量取得
    $e1c = new Edit1Calculator();
    $e1c->setProcessItem($process_item_id, true);
    $this->num = $e1c->getNum();
    $this->e1cInputs = $e1c->getInputs();

    $this->e1cResult = $e1c->getResult();
  }

  /**
   * E1C結果取得
   * @return mixed
   */
  public function getE1cResult()
  {
    return $this->e1cResult;
  }

  /**
   * 期間内使用数量
   *
   * @return mixed
   */
  public function getTermUsage(): mixed
  {
    $process_term_usage = null;
    if($this->process_item_id) {
      // 施工期間内使用数量
      $process_term_usage = $this->process_term_usages
        ->where('process_item_id', $this->process_item_id)
        ->first();
    }
    return $process_term_usage;
  }

  /**
   * 材料費：累計使用数量 / 外注費：累計出来高数量
   *
   * @param $usage
   * @return float|int
   */
  public function getUsageSum($usage): float|int
  {
    // 施工期間内使用数量
    foreach($this->pre_process_terms as $term) {
      foreach($term->usages as $term_usage) {
        if($this->process_item_id == $term_usage->process_item_id) {
          $usage += $term_usage->usage;
        }
      }
    };
    return $usage;
  }

  /**
   * 材料費/外注費（下記以外）：予算数量×全体進捗率
   *
   * @return float|int
   */
  public function getBudgetProgress(): float|int
  {
    return $this->num * $this->process_term->overall_rate / 100;
  }

  /**
   * 1 準備・撤去工 / 2 法面清掃工 / 7 場内清掃工
   * 外注費：進捗率 = （項目の累計出来高数量 × 項目の単価） ÷（同一項目内の外注費予算合計）
   *
   * @param $usage_sum
   * @return float|int
   */
  public function getProgressRate($usage_sum): float|int
  {
    // 同一項目内の外注費予算合計
    // 同一工程内の外注費予算合計 ではないか？
    $process_item = ProcessItem::find($this->process_item_id);
    $process_items = ProcessItem::where('process_id', $process_item->process_id)->get();
    $e1c = new Edit1Calculator();
    $total = 0;
    foreach($process_items as $process_item) {
      $e1c->setProcessItem($process_item->id, true);
      $res = $e1c->getResult();
      $total += $res['result'];
    }

    // ［予算］単価
    $price = $this->e1cInputs[15] ?? 0;

    $progress_rate = 0;
    if($total) {
      $progress_rate = round($usage_sum * $price / $total, 2);
    }

    return $progress_rate;
  }

  /**
   * 材料費：ロス率 = 「累計使用数量」÷（「予算数量×全体進捗率」 ÷ 「予算入力のロス率」）
   *
   * @param $usage
   * @return float|int
   */
  public function getLossRatio($usage): float|int
  {
    $loss_ratio = 0;
    // 累計使用数量
    $total_usage = $this->getUsageSum($usage);
    // 予算数量×全体進捗率
    $budget_progress = $this->getBudgetProgress();
    // 予算入力のロス率
    $rate = $this->e1cInputs[4] ?? 0;

    if($budget_progress * $rate) {
      $loss_ratio = round($total_usage / ($budget_progress / $rate) * 1000) / 1000;
    }
    return $loss_ratio;
  }

  /**
   * 登録済み工程期間
   *
   * @return mixed
   */
  public function getProcessTerms(): mixed
  {
    return $this->process_terms;
  }

  /**
   * 期間内歩掛り（㎡/人・日） = 範囲内面積(㎡) ÷ 期間内施工人工
   *
   * @param $real_day
   * @param $man_hour
   * @return float|int
   */
  public function getTermRate($real_day, $man_hour): float|int
  {
    switch($this->process_type) {
      // 法枠組立工
      case 4:
        // 範囲内延長(m)
        $target = $this->process_term->length_within;
        break;
      // 法枠吹付工
      case 5:
      default:
        // 範囲内面積(㎡)
        $target = $this->process_term->area_within;
    }
    $term_rate = 0;
    if($man_hour > 0) {
      $term_rate = round($target / $man_hour * 1000) / 1000;
    }
    return $term_rate;
  }

  /**
   * 累計歩掛り（㎡/人・日） = 施工終了総面積(㎡) ÷ 期間内施工人工
   *
   * @param $real_day
   * @param $man_hour
   * @return float|int
   */
  public function getTotalRate($real_day, $man_hour): float|int
  {

    switch($this->process_type) {
      // 法枠組立工
      case 4:
      // 法枠吹付工
      case 5:
        // 当施工期間を含む「範囲内延長(m)	」
        $targets = $this->pre_total_length_within;
        break;
      default:
        // 当施工期間を含む「範囲内面積(㎡)	」
        $targets = $this->pre_total_area_within;
    }
    // 当施工期間を含まない「期間内施工人工」 ＋ 当施工期間「期間内施工人工」
    $man_hours = $this->pre_total_man_hour + $man_hour;
    // 当施工期間を含まない「期間内稼働日数」 ＋ 当施工期間「期間内稼働日数」
    //$real_days = $this->pre_total_real_day + $real_day;

    $total_rate = 0;
    if($man_hours) {
      $total_rate = round($targets / $man_hours * 1000) / 1000;
    }
    return $total_rate;
  }

  /**
   * 累計した期間内施工人工
   *
   * @param $man_hour
   * @return float|int
   */
  public function getTotalManHour($man_hour): float|int
  {
    return $this->pre_total_man_hour + $man_hour;
  }
}
