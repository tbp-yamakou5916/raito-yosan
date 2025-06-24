<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ExpenseItem;
use App\Models\Master\Unit;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UnitController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.master.unit.index';
  // resources/views/
  protected string $view = 'admin.master.unit.';
  // resources/lang/ja/admin.php
  protected $title;
  protected $expense_items;

  public function __construct()
  {
    $this->title = __('admin.master.unit._menu');
    $this->expense_items = ExpenseItem::selectArray();
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
    $data = Unit::with($load)
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
      'sequence' => Unit::max('sequence') + 10,
      'expense_items' => $this->expense_items,
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    Unit::create($request->all());

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param Unit $unit
   * @return View
   */
  public function edit(Unit $unit): View
  {
    $items = [
      'datum' => $unit,
      'expense_items' => $this->expense_items,
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * @param Request $request
   * @param Unit $unit
   * @return RedirectResponse
   */
  public function update(Request $request, Unit $unit): RedirectResponse
  {
    $unit->update($request->all());

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param Unit $unit
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(Unit $unit): RedirectResponse
  {
    $unit->deleted_by = Auth::id();
    $unit->save();
    $unit->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }
}
