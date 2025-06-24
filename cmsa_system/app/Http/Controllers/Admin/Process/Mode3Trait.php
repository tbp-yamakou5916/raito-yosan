<?php

namespace App\Http\Controllers\Admin\Process;

use App\Libs\Calculator\Edit3Calculator;
use App\Libs\projectParams;
use App\Models\Master\ExpenseCustomItem;
use App\Models\Master\ExpenseItem;
use App\Models\Process;
use App\Models\ProcessComment;

trait Mode3Trait
{
  /**
   * mode3：分析実行
   *
   * @param $request
   * @param $process_type
   * @return mixed
   */
  private function getItems3($process_type) {
    $pp = new projectParams();

    // フリーフォーム取得
    $free_form = $pp->nowFreeForm();

    // 工程データ
    $process = Process::where('free_form_id', $free_form->id)
      ->where('process_type', $process_type)
      ->first();

    // 計算クラス
    $e3c = new Edit3Calculator($process->id);

    $outsourcing_items = collect();
    if($process_type==1) {
      // 2 昇降階段設置・撤去工
      // 3 法肩単管柵設置・撤去工
      // 4 親綱設置・撤去工
      $targets = [2, 3, 4];

      // 費用項目
      $process_items = $process->process_items->whereIn('expense_item_id', $targets);

      $outsourcing_items = ExpenseItem::whereIn('id', $targets)->get()
        ->map(function($item) use($process_items) {
          $process_item = $process_items->where('expense_item_id', $item->id)->first();
          $item->values = [
            'num' => $process_item->num ?? 0,
          ];
          return $item;
        });
    }
    // コメント
    $comments = $process->process_terms
      ->map(function($term) {
        $comments = $term->comments;
        $process_type =  $term->process->process_type;
        foreach ($comments as $comment) {
          $comment->process_type = $process_type;
          $comment->start = $term->start;
          $comment->end = $term->end;
          $comment->construction_term_label = $term->constructionTermLabel;
        }
        return $comments;
      })->flatten()
      ->sortByDesc('start');

    return [
      'datum' => $process,
      // 乖離理由
      'reasons'=> $process->comments->where('process_type',  $process_type),
      // モード
      'mode_num' => 3,
      // 工程タイプ
      'process_type' => $process_type,
      // 工程メニュー
      'process_nav' => $free_form->processes->filter(function ($process) {
        return $process->process_terms->isNotEmpty();
      }),
      // 費用項目
      'expense_items' => $e3c->getExpenseItems(),
      // コメント
      'comments' => $comments,
      // 作業情報
      'base_result' => $e3c->getBaseResult(),
      // 材料ロス/材料費
      'material_result' => $e3c->getMaterialResult(),
      // 歩掛り
      'productivity_result' => $e3c->getProductivity(),
      // 外注費
      'outsourcing_result' => $e3c->getOutsourcingResult(),
      // 歩掛り / 外注費
      'outsourcing_items' => $outsourcing_items,
    ];
  }

  /**
   * @param $request
   * @param $process
   */
  private function update3($request, $process): void
  {
    $updated_by = $request->get('updated_by');
    $process_type = $request->get('process_type');

    // 乖離理由
    $reasons = $request->get('reason');
    foreach ($reasons as $cost_type => $reason) {
      $res = ProcessComment::where('process_id', $process->id)
        ->where('process_type', $process_type)
        ->where('cost_type', $cost_type)
        ->first();
      $items = [
        'process_id' => $process->id,
        'process_type' => $process_type,
        'cost_type' => $cost_type,
        'comment' => $reason,
      ];
      if($res) {
        $items['updated_by'] = $updated_by;
        $res->update($items);
      } else {
        $items['created_by'] = $updated_by;
        ProcessComment::create($items);
      }
    }
  }
}
