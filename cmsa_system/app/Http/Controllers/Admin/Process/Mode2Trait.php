<?php

namespace App\Http\Controllers\Admin\Process;

use App\Libs\Calculator\Edit2Calculator;
use App\Libs\projectParams;
use App\Models\Master\ExpenseCustomItem;
use App\Models\Master\ExpenseItem;
use App\Models\Process;
use App\Models\ProcessTerm;
use App\Models\ProcessTermComment;
use App\Models\ProcessTermUsage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait Mode2Trait
{
  /**
   * mode2：実績入力
   *
   * @param $process_type
   * @param $process_term
   * @return array
   */
  private function getItems2($process_type, $process_term): array
  {
    // プロジェクトクラス
    $pp = new projectParams();

    // フリーフォーム取得
    $free_form = $pp->nowFreeForm();

    // 工程データ
    $process = Process::where('free_form_id', $free_form->id)
      ->where('process_type', $process_type)
      ->first();

    // 費用項目
    $process_items = $process->process_items;

    // 計算クラス
    $e2c = new Edit2Calculator($process_term->id);

    $cost_types = [1, 2];

    $expense_items = collect();
    for($i=1; $i<=2; $i++) {
      if($i==1) {
        // 費用項目マスター
        $data = ExpenseItem::where('process_type', $process_type)
          ->where('invalid', 0)
          ->whereIn('cost_type', $cost_types)
          ->orderBy('sequence')
          ->get();
        $column_name = 'expense_item_id';
      } else {
        // カスタム費用項目マスター
        $project_id = $pp->nowProjectID();
        $data = ExpenseCustomItem::where('process_id', $process->id)
          ->where('project_id', $project_id)
          ->whereIn('cost_type', $cost_types)
          ->orderBy('created_at')
          ->get();
        $column_name = 'expense_custom_item_id';
      }
      // そもそも何も入力の無いデータを除去
      $data = $data->filter(function ($item) use($process_items, $process_type, $column_name) {
        // 外注費はそのまま
        if($item->cost_type == 2) {
          return true;
        } else {
          return $process_items->where($column_name, $item->id)->first();
        }
      });

      // valuesの設定
      $data = $data->map(function ($item) use($e2c, $process_items, $process_term, $column_name, $process_type) {
        $process_item = $process_items->where($column_name, $item->id)->first();
        return $this->setEdit2Values($e2c, $item, $process_item, $process_type);
      });

      // 予算金額が0のデータを除去
      $data = $data->filter(function ($item) use($process_type) {
        // 外注費はそのまま
        if($item->cost_type == 2) {
          return $item->values['num'];
        } else {
          return $item->values['price'];
        }
      });

      // 費用項目のマージ
      $expense_items = $expense_items->concat($data)->values();
    }
    // 期間内施工人工
    $man_hour = $expense_items->where('cost_type', 2)->sum('term_man_hour');

    // 期間内歩掛り
    $term_rate = $e2c->getTermRate($process_term->real_day, $process_term->man_hour);

    // 累計歩掛り
    $total_rate = $e2c->getTotalRate($process_term->real_day, $process_term->man_hour);

    // 登録済み工程期間
    $process_terms = $e2c->getProcessTerms();
    if($process_terms->isNotEmpty()) {
      $process_terms = $process_terms->sortByDesc('end');
    }

    return [
      'datum' => $process,
      // モード
      'mode_num' => 2,
      // 工程期間
      'process_term' => $process_term,
      // 期間内施工人工
      'man_hour' => $man_hour,
      // 登録済み工程期間
      'process_terms' => $process_terms,
      // 工程タイプ
      'process_type' => $process_type,
      // 費用項目マスター
      'expense_items' => $expense_items,
      // 期間内歩掛り
      'term_rate' => $term_rate,
      // 累計歩掛り
      'total_rate' => $total_rate,
      // コメント用要因区分
      'conditions' => $this->getConditions(),
    ];
  }

  /**
   * valuesの設定
   *
   * @param $e2c
   * @param $item
   * @param $process_item
   * @param $process_type
   * @return mixed
   */
  private function setEdit2Values($e2c, $item, $process_item, $process_type): mixed
  {
    $values = [
      'process_item_id' => null,
      'process_term_usage_id' => null,
      // 期間内使用数量
      'usage' => 0,
      // 実施人工
      'man_hour' => 0,
      // 累計使用数量
      'usage_sum' => 0,
      // 予算数量×全体進捗率
      'budget_progress' => 0,
      // ロス率
      'loss_ratio' => '-',
      // 単位
      'unit_html' => null,
      // 予算金額
      'price' => 0,
    ];

    if($process_item) {
      // 材料費
      $e2c->setProcessItem($process_item->id);

      // 期間内使用数量
      $process_term_usage = $e2c->getTermUsage();
      $usage = $process_term_usage->usage ?? 0;

      // 累計使用数量
      $usage_sum = $e2c->getUsageSum($usage);

      // 予算数量×全体進捗率
      if(in_array($process_type, [1, 2, 7])) {
        $budget_progress = $e2c->getProgressRate($usage_sum);
      } else {
        $budget_progress = $e2c->getBudgetProgress();
      }

      // ロス率
      $loss_ratio = $e2c->getLossRatio($usage, $item->cost_type);

      $e1cResult = $e2c->getE1cResult();
      $values = [
        'process_item_id' => $process_item->id ?? null,
        'process_term_usage_id' => $process_term_usage->id ?? null,
        // 期間内使用数量 / 期間内出来高数量
        'usage' => $usage,
        // 実施人工
        'man_hour' => $process_term_usage->man_hour ?? 0,
        // 累計使用数量
        'usage_sum' => $usage_sum,
        // 予算数量×全体進捗率
        'budget_progress' => $budget_progress,
        // ロス率
        'loss_ratio' => $loss_ratio > 0 ?: '-',
        // 単位
        'unit_html' => $process_item->unitHtml ?? null,
        // 予算金額
        'price' => $e1cResult['result'],
      ];
    }

    if($item->cost_type==1) {
      // 規格
      $values['standard_label'] = $process_item->standardLabel ?? null;
    } else {
      // 予定数量
      $values['num'] = $process_item->num ?? 0;
    }
    $item->values = $values;
    // 期間内実施人工
    $item->term_man_hour = $values['man_hour'];

    return $item;
  }

  /**
   * @param $request
   */
  private function update2($request): void
  {
    $updated_by = $request->get('updated_by');

    // 工程期間
    $process_term = ProcessTerm::find($request->get('process_term_id'));
    $terms = $request->get('process_term');
    $terms['updated_by'] = $updated_by;
    $process_term->update($terms);

    // 材料費 / 外注費
    $process_term_usages = $process_term->usages;
    $items = $request->get('items');
    $term_man_hour = 0;
    if($items) {
      foreach ($request->get('items') as $item) {
        $p_item = null;
        if($item['process_term_usage_id']) {
          $p_item = $process_term_usages->where('id', $item['process_term_usage_id'])->first();
        }
        if($p_item) {
          $item['updated_by'] = $updated_by;
          $p_item->update($item);
        } else {
          $item['process_term_id'] = $process_term->id;
          $item['created_by'] = $updated_by;
          ProcessTermUsage::create($item);
        }
        //
        if(isset($item['man_hour'])) $term_man_hour += $item['man_hour'];
      }
    }

    // 期間内施工人工の保存
    $process_term->man_hour = $term_man_hour;
    $process_term->save();

    // コメント
    $comments = $request->get('comment');
    foreach ($comments as $cost_type => $cost_group) {
      foreach ($cost_group as $comment) {
        // どちらも設定がない場合
        if(!$comment['condition_key'] && !$comment['comment']) {
          if($comment['id']) {
            ProcessTermComment::find($comment['id'])->delete();
          }
          continue;
        }
        // 更新又は新規追加
        $res = null;
        if($comment['id']) {
          $res = ProcessTermComment::find($comment['id']);
        }
        $comment['cost_type'] = $cost_type;
        if($res) {
          $comment['updated_by'] = $updated_by;
          $res->update($comment);
        } else {
          $comment['process_term_id'] = $process_term->id;
          $comment['created_by'] = $updated_by;
          ProcessTermComment::create($comment);
        }
      }
    }
  }

  /**
   * セレクト用要因区分作成
   *
   * @return array
   */
  private function getConditions(): array
  {
    $conditions = collect(__('condition'))->map(function($item, $key) {
      return [
        'key' => $key,
        'label' => $item['short'] ?? $item['label'],
      ];
    })->pluck('label', 'key')->toArray();
    return $conditions + ['other' => 'その他'];

  }

  /**
   * ［非同期］mode2：コメントフォーム追加
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function addCommentForm(Request $request): JsonResponse
  {
    $cost_type = $request->get('costType');
    $num = $request->get('num');
    $html = view('admin.process.partials.edit2.comment', [
      'cost_type' => $cost_type,
      // コメント用要因区分
      'conditions' => $this->getConditions(),
      'num' => $num++
    ])->render();

    return response()->json(['num' => $num, 'html' => $html]);
  }

  /**
   * ［非同期］mode2：歩掛かり取得
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function getRate(Request $request): JsonResponse
  {
    $process_term_id =  $request->get('process_term_id');
    $real_day =  $request->get('realDay');
    $man_hour =  $request->get('manHour');

    // 計算クラス
    $e2c = new Edit2Calculator($process_term_id);
    return response()->json([
      'termRate' => $e2c->getTermRate($real_day, $man_hour),
      'totalRate' => $e2c->getTotalRate($real_day, $man_hour)
    ]);
  }

  /**
   * ［非同期］mode2：材料費各種計算
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function materialCalculate(Request $request): JsonResponse
  {
    $process_type =  $request->get('process_type');
    $process_term_id =  $request->get('process_term_id');
    $process_item_id =  $request->get('process_item_id');
    $cost_type =  $request->get('cost_type');
    $usage =  $request->get('usage');
    $man_hour =  $request->get('man_hour');

    // 計算クラス
    $e2c = new Edit2Calculator($process_term_id);
    $e2c->setProcessItem($process_item_id);

    // 材料費：累計使用数量 / 外注費：累計出来高数量
    $usage_sum = $e2c->getUsageSum($usage);

    // 材料費：ロス率 = 期間内使用数量 ÷（予算数量×進捗率）
    $loss_ratio = 0;
    if($cost_type==1) {
      $loss_ratio = $e2c->getLossRatio($usage);
    }

    // 外注費：進捗率
    $progress_rate = 0;
    if($cost_type==2 && in_array($process_type, [1, 2, 7])) {
      $progress_rate = $e2c->getProgressRate($usage_sum);
    }

    return response()->json([
      'usageSum' => $usage_sum,
      'lossRatio' => $loss_ratio > 0 ? $loss_ratio : '-',
      'progressRate' => $progress_rate,
    ]);
  }
}
