<?php

namespace App\Http\Controllers\Admin\Process;

use App\Libs\Calculator\Edit1Calculator;
use App\Libs\projectParams;
use App\Models\Master\ExpenseCustomItem;
use App\Models\Master\ExpenseItem;
use App\Models\Master\FfDefault;
use App\Models\Process;
use App\Models\ProcessItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait Mode1Trait
{
  /**
   * mode1：予算入力
   *
   * @param $process_type
   * @return array
   */
  private function getItems1($process_type): array
  {
    $pp = new projectParams();
    $project_id = $pp->nowProjectID();

    // フリーフォーム取得
    $free_form = $pp->nowFreeForm();

    // 工程が存在しない場合
    if($free_form->processes->isEmpty()) {
      foreach (__('array.process_type.params') as $num => $label) {
        $items = [
          'free_form_id' => $free_form->id,
          'process_type' => $num,
        ];
        Process::create($items);
      }
    }

    // FFデフォルト
    $ff_default = FfDefault::where('ff_category_id', $free_form->ff_category_id)->get();

    // 工程データ
    $query = Process::where('free_form_id', $free_form->id);
    if($process_type != 'all') {
      $query->where('process_type', $process_type);
    }
    $processes = $query->get();

    $all_expense_items = collect();
    foreach ($processes as $process) {
      $process_items = $process->process_items;
      // 費用項目マスター
      $expense_items = ExpenseItem::where('process_type', $process->process_type)
        ->where('invalid', 0)
        ->orderBy('sequence')
        ->get()
        ->map(function ($item) use($process_items, $ff_default, $process) {
          $process_item = $process_items->where('expense_item_id', $item->id)->first();
          $default = $ff_default->where('expense_item_id', $item->id)->first();
          $item->values = [
            'process_id' => $process->id ?? null,
            'process_item_id' => $process_item->id ?? null,
            // デフォルト
            'default' => $default->standard_id ?? null,
            // 規格ID
            'standard_id' => $process_item->standard_id ?? null,
            // 規格
            'standard_name' => $process_item->standard_name ?? null,
            // 数量
            'num' => $process_item->num ?? $item->default_num ?? null,
            // 単価
            'price' => $process_item->price ?? null,
            // 想定人工
            'popularity' => $process_item->popularity ?? $item->default_popularity ?? null,
            // ロス率
            'rate' => $process_item->rate ?? $item->default_rate ?? null,
            // 作業割合/日
            'rate2' => $process_item->rate2 ?? $item->default_rate2 ?? 1.0,
            // 作業人数
            'worker_num' => $process_item->worker_num ?? null,
            // カスタム費用項目
            'is_custom' => false,
            // 小数点第1位可フラグ
            'is_float' => $item->is_float ?? 0,
            // ［材料］単位 ※expense_item_id：1 仮設材他（親綱・アンカー）のみで使用
            'unit_id' => $process_item->price ?? 0,
          ];
          return $item;
        });
      $all_expense_items = $all_expense_items->concat($expense_items)->values();

      // カスタム費用項目マスター
      $expense_custom_items = ExpenseCustomItem::where('process_id', $process->id)
        ->where('project_id', $project_id)
        ->orderBy('created_at')
        ->get()
        ->map(function ($item) use($process_items, $process) {
          $process_item = $process_items->where('expense_custom_item_id', $item->id)->first();
          // allタイトル用
          $item->process_type = $process->process_type;
          $item->values = [
            'process_id' => $process->id ?? null,
            'process_item_id' => $process_item->id ?? null,
            // デフォルト
            'default' => null,
            // 規格ID
            'standard_id' => null,
            // 規格
            'standard_name' => $process_item->standard_name ?? null,
            // 数量
            'num' => $process_item->num ?? null,
            // 単価
            'price' => $process_item->price ?? null,
            // 想定人工
            'popularity' => $process_item->popularity ?? null,
            // ロス率
            'rate' => $process_item->rate ?? null,
            // 作業割合/日
            'rate2' => $process_item->rate2 ?? 1.0,
            // 作業人数
            'worker_num' => $process_item->worker_num ?? null,
            // カスタム費用項目
            'is_custom' => true,
            // 小数点第1位可フラグ
            'is_float' => 0,
          ];
          return $item;
        });
      $all_expense_items = $all_expense_items->concat($expense_custom_items)->values();
    }

    // 上記項目増やした際には、下記にも要設定
    // app\Http\Controllers\Admin\Master\ExpenseCustomItemController.php

    // 新規作成時リレーションでは取得できない
    $process_nav = Process::where('free_form_id', $free_form->id)->get();

    return [
      // ループの最後の工程が設定される
      'datum' => $processes->first(),
      // モード
      'mode_num' => 1,
      // 工程タイプ
      'process_type' => $process_type,
      // 工程メニュー
      'process_nav' => $process_nav,
      // 費用項目マスター
      'expense_items' => $all_expense_items,
    ];
  }

  /**
   * @param $request
   * @param $process
   * @return array
   */
  private function update1($request, $process): array
  {
    $alerts = [];
    $process_type =  $request->get('process_type');

    // 更新
    if($process_type == 'all') {
      $processes = $process->free_form->processes;
      $process_items = collect();
      foreach ($processes as $process) {
        $process_items = $process_items->concat($process->process_items)->values();
        if($process->is_not_carried) continue;
        if(!$process->schedule_start ||  !$process->schedule_end ||  !$process->schedule_day) {
          if(empty($alerts)) {
            $alerts[] = '下記の必須項目を入力してください。';
          }
          $str = __('array.process_type.params.' . $process->process_type) . '：';
          if(!$process->schedule_start) {
            $str .= '「' . __('admin.process.schedule_start') . '」';
          }
          if(!$process->schedule_end) {
            $str .= '「' . __('admin.process.schedule_end') . '」';
          }
          if(!$process->schedule_day) {
            $str .= '「' . __('admin.process.schedule_day') . '」';
          }
          $alerts[] = $str;
        }
      }
    } else {
      $process->update($request->all());
      $process_items = $process->process_items;
    }

    $updated_by = $request->get('updated_by');

    // 材料費 / 外注費
    foreach ($request->get('items') as $item) {
      $p_item = null;
      if($item['process_item_id']) {
        $p_item = $process_items->where('id', $item['process_item_id'])->first();
      }
      // なぜか両方入るのか？？
      if(isset($item['expense_custom_item_id'])) {
        $item['expense_item_id'] = null;
      }
      if($p_item) {
        $item['updated_by'] = $updated_by;
        $p_item->update($item);
      } else {
        $item['created_by'] = $updated_by;
        ProcessItem::create($item);
      }
    }

    return $alerts;
  }

  /**
   * ［非同期］mode1：計算
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function calculate(Request $request): JsonResponse
  {
    $e1c = new Edit1Calculator();
    $e1c->setRequest($request);
    return response()->json($e1c->getResult());
  }
}
