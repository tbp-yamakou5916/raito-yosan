<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Libs\projectParams;
use App\Models\Master\ExpenseItem;
use App\Models\Master\Unit;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExpenseItemController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.master.expense_item.index';
  // resources/views/
  protected string $view = 'admin.master.expense_item.';
  // resources/lang/ja/admin.php
  protected $title;

  public function __construct()
  {
    $this->title = __('admin.master.expense_item._menu');
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
    $data = ExpenseItem::with($load)
      ->orderBy('process_type')
      ->orderBy('cost_type')
      ->orderBy('sequence')
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
    $items = [
      'sequence' => ExpenseItem::max('sequence') + 10,
      // 単位
      'units' => Unit::orderBy('sequence')->pluck('title', 'id')->toArray(),
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    ExpenseItem::create($request->all());

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param ExpenseItem $expense_item
   * @return View
   */
  public function edit(ExpenseItem $expense_item): View
  {
    $items = [
      'datum' => $expense_item,
      // 単位
      'units' => Unit::orderBy('sequence')->pluck('title', 'id')->toArray(),
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * @param Request $request
   * @param ExpenseItem $expense_item
   * @return RedirectResponse
   */
  public function update(Request $request, ExpenseItem $expense_item): RedirectResponse
  {
    $expense_item->update($request->all());

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param ExpenseItem $expense_item
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(ExpenseItem $expense_item): RedirectResponse
  {
    $expense_item->deleted_by = Auth::id();
    $expense_item->save();
    $expense_item->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }
}
