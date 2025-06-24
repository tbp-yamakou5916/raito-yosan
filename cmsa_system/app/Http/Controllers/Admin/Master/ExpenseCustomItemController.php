<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Libs\projectParams;
use App\Models\Master\ExpenseCustomItem;
use App\Models\ProcessItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCustomItemController extends Controller
{
  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse
  {
    // 値取得
    $num = $request->get('num');
    $cost_type = $request->get('cost_type');
    $title = $request->get('title');

    // プロジェクトID取得
    $pp = new projectParams();
    $project_id = $pp->nowProjectId();
    $request->merge(['project_id' => $project_id]);

    // 有効フラグ
    $is_valid = (bool) $title;
    $html = null;

    if($is_valid) {
      $item = ExpenseCustomItem::create($request->all());
      $item->values = [
        'process_id' => $item->process_id ?? null,
        'process_item_id' => null,
        // mode1 予算入力 ***********************
        // デフォルト
        'default' => null,
        // 規格ID
        'standard_id' => null,
        // 規格
        'standard_name' => null,
        // 数量
        'num' => null,
        // 単価
        'price' => null,
        // 想定人工
        'popularity' => null,
        // ロス率
        'rate' => null,
        // 作業割合/日
        'rate2' => 1.0,
        // 作業人数
        'worker_num' => null,
        // カスタム費用項目
        'is_custom' => true,
        // 小数点第1位可フラグ
        'is_float' => 0,
      ];
      if($cost_type==1) {
        $html = view('admin.process.partials.edit1.material', [
          'item' => $item, 'num' => $num])->render();
      } else {
        $html = view('admin.process.partials.edit1.outsourcing', ['item' => $item, 'num' => $num])->render();
      }
    }

    return response()->json(['isValid' => $is_valid, 'html' => $html, 'targetNum' => $num, 'num' => ++$num]);
  }
  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function destroy(Request $request): JsonResponse
  {
    // 値取得
    $expense_custom_item_id = $request->get('expense_custom_item_id');

    // 削除
    $item = ExpenseCustomItem::find($expense_custom_item_id);
    $item->deleted_by = Auth::id();
    $item->save();
    $item->delete();

    // 一つしかないはずだが、念のため
    $items = ProcessItem::where('expense_custom_item_id', $expense_custom_item_id)->get();
    foreach ($items as $item) {
      $item->delete();
    }

    return response()->json(['isValid' => true]);
  }
}
