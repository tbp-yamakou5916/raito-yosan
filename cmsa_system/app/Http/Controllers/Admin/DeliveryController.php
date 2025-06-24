<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\CheckProject;
use App\Libs\projectParams;
use App\Models\Delivery;
use App\Models\Process;
use App\Models\ProcessItem;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DeliveryController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.delivery.index';
  // resources/views/
  protected string $view = 'admin.delivery.';
  // resources/lang/ja/admin.php
  protected $title;

  public function __construct()
  {
    $this->title = __('admin.delivery._menu');
  }

  /**
   * @param Request $request
   * @return View|RedirectResponse
   */
  public function index(Request $request): View|RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('delivery');
    $result = $cp->check();
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    // 表示形式
    // 1：フリーフォーム単位で使用予定の工程費用項目の実績付き資材一覧
    $view_type = null;
    if($request->has('view_type')) {
      $view_type = $request->get('view_type');
    }

    // フリーフォーム単位の工程費用項目内の資材一覧の取得
    $data = $this->getProcessItems();

    if(!$view_type) {
      // 搬入実績のあるデータ一覧
      $ids = $data->pluck('id')->toArray();
      $loads = [
        'process_item.process',
        'process_item',
        'process_item.expense_item',
        'process_item.expense_custom_item',
        'process_item.standard',
      ];
      $data = Delivery::whereIn('process_item_id', $ids)
        ->with($loads)
        ->orderBy('delivered_at', 'desc')
        ->get();
    }

    // 各種ID
    $pp = new projectParams();
    $now_project_id = $pp->nowProjectID();
    $now_free_form_id = $pp->nowFreeFormID();
    $items = [
      'now_project_id' => $now_project_id,
      'now_free_form_id' => $now_free_form_id,
      'view_type' => $view_type,
      'data' => $data,
    ];
    return view($this->view . 'index', $items);
  }

  /**
   * @return View|RedirectResponse
   */
  public function create(): View|RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('delivery');
    $result = $cp->check();
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    // セレクト用データ取得
    $selects = $this->getProcessItems(true);

    $items = [
      'view_type' => 0,
      'selects' => $selects,
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * @param ProcessItem $process_item
   * @return View|RedirectResponse
   */
  public function add(ProcessItem $process_item): View|RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('delivery');
    $result = $cp->check();
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    // セレクト用データ取得
    $selects = [$process_item->id => $this->makeItemLabel($process_item)];

    $items = [
      'view_type' => 1,
      'selects' => $selects,
      'deliveries' => $process_item->deliveries,
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    Delivery::create($request->all());

    $view_type = $request->get('view_type');
    $params = [];
    if($view_type==1) {
      $params = ['view_type' => 1];
    }

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route($this->redirect, $params)
      ->with('msg_success', $message);
  }

  /**
   * @param Delivery $delivery
   * @return View|RedirectResponse
   */
  public function edit(Delivery $delivery): View|RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('delivery');
    $result = $cp->check();
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    // セレクト用データ取得
    $selects = $this->getProcessItems(true);

    $items = [
      'view_type' => 0,
      'selects' => $selects,
      'datum' => $delivery,
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * @param Request $request
   * @param Delivery $delivery
   * @return RedirectResponse
   */
  public function update(Request $request, Delivery $delivery): RedirectResponse
  {
    $delivery->update($request->all());

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param Delivery $delivery
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(Delivery $delivery): RedirectResponse
  {
    $delivery->deleted_by = Auth::id();
    $delivery->save();
    $delivery->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * フリーフォーム単位の工程費用項目内の資材一覧の取得
   * @param bool $is_select
   * @return mixed
   */
  private function getProcessItems(bool $is_select = false): mixed
  {
    $pp = new projectParams();
    $now_free_form_id = $pp->nowFreeFormID();

    // 対象の工程ID
    $process_ids = Process::where('free_form_id', $now_free_form_id)
      ->pluck('id')
      ->toArray();

    // フリーフォーム単位で使用予定の工程費用項目内の資材
    $loads = [
      'process',
      'deliveries',
      'expense_item',
      'expense_custom_item',
      'standard',
    ];
    $process_items = ProcessItem::whereIn('process_id', $process_ids)
      ->where('cost_type', 1)
      ->with($loads)
      ->get();

    if(!$is_select) {
      return $process_items;
    }

    // セレクト用配列作成
    return $process_items->map(function($item) {
      $item->label = $this->makeItemLabel($item);
      if($item->unitHtml) {
        $item->label .= '（' . $item->unitHtml . '）';
      }
      return $item;
    })->pluck('label', 'id')->toArray();
  }

  /**
   * セレクト用ラベル作成
   *
   * @param $item
   * @return string
   */
  private function makeItemLabel($item): string
  {
    return '［' . $item->processLabel . '］' . $item->title . ' ' . $item->standardLabel;
  }

  /**
   * ［非同期］単位取得
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function getUnit(Request $request): JsonResponse
  {
    $process_item_id = $request->get('process_item_id');
    $process_item = ProcessItem::find($process_item_id);

    $item = null;
    if($process_item->expense_item_id) {
      $item = $process_item->expense_item;
    } elseif($process_item->expense_custom_item_id) {
      $item = $process_item->expense_custom_item;
    }

    return response()->json([$item->unitHtml ?? '-']);
  }
}
