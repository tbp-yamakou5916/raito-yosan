<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ExpenseItem;
use App\Models\Master\FfCategory;
use App\Models\Master\FfDefault;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FfDefaultController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.master.ff_default.index';
  // resources/views/
  protected string $view = 'admin.master.ff_default.';
  // resources/lang/ja/admin.php
  protected $title;
  protected $expense_items;

  public function __construct()
  {
    $this->title = __('admin.master.ff_default._menu');
    $this->expense_items = ExpenseItem::selectArray(true);

  }

  /**
   * @return View
   */
  public function index(): View
  {
    $load = [
      'user_created_by',
      'user_updated_by',
      'user_deleted_by',
    ];
    $data = FfDefault::with($load)
      ->orderBy('ff_category_id')
      ->orderBy('expense_item_id')
      ->orderBy('standard_id')
      ->get();

    $items = [
      'data' => $data,
    ];
    return view($this->view . 'index', $items);
  }

  /**
   * @return View
   */
  public function create(): View
  {
    // 重複チェック用データ
    $defaults = FfDefault::all()->map(function($d) {
      return [
        'ff_category_id' => $d->ff_category_id,
        'expense_item_id' => $d->expense_item_id,
      ];
    })->toArray();

    $items = [
      'expense_items' => $this->expense_items,
      'ff_categories' => FfCategory::selectArray(),
      'defaults' => $defaults,
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    FfDefault::create($request->all());

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param FfDefault $ff_default
   * @return View
   */
  public function edit(FfDefault $ff_default): View
  {
    $items = [
      'datum' => $ff_default,
      'expense_items' => $this->expense_items,
      'ff_categories' => FfCategory::selectArray(),
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * @param Request $request
   * @param FfDefault $ff_default
   * @return RedirectResponse
   */
  public function update(Request $request, FfDefault $ff_default): RedirectResponse
  {
    $ff_default->update($request->all());

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param FfDefault $ff_default
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(FfDefault $ff_default): RedirectResponse
  {
    $ff_default->deleted_by = Auth::id();
    $ff_default->save();
    $ff_default->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * ［非同期］規格名取得
   * @param Request $request
   * @return JsonResponse
   */
  public function getStandard(Request $request)
  {
    $expense_item_id = $request->get('expense_item_id');
    $standard_id = $request->get('standard_id');
    $html = '<option value="">' . __('common.empty') . '</option>';
    $is_disabled = 1;
    if ($expense_item_id) {
      $expense_item = ExpenseItem::find($expense_item_id);
      if($expense_item) {
        $standards = $expense_item
          ->standards
          ->where('invalid', 0)
          ->sortBy('sequence');
        if($standards->isNotEmpty()) {
          $is_disabled = 0;
          foreach ($standards as $standard) {
            $html .= '<option value="' . $standard->id . '"';
            if($standard_id == $standard->id) {
              $html .= ' selected';
            }
            $html .= '>' . $standard->title . '</option>';
          }
        }
      }
    }
    $items = [
      'isDisabled' => $is_disabled,
      'html' => $html
    ];
    return response()->json($items);
  }
}
