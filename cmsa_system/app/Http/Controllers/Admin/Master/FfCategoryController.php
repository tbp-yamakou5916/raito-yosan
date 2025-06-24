<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\FfCategory;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FfCategoryController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.master.ff_category.index';
  // resources/views/
  protected string $view = 'admin.master.ff_category.';
  // resources/lang/ja/admin.php
  protected $title;

  public function __construct()
  {
    $this->title = __('admin.master.ff_category._menu');

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
    $data = FfCategory::with($load)->orderBy('sequence')->get();

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
    $items = [];
    return view($this->view . 'create', $items);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    FfCategory::create($request->all());

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param FfCategory $ff_category
   * @return View
   */
  public function edit(FfCategory $ff_category): View
  {
    $items = [
      'datum' => $ff_category,
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * @param Request $request
   * @param FfCategory $ff_category
   * @return RedirectResponse
   */
  public function update(Request $request, FfCategory $ff_category): RedirectResponse
  {
    $ff_category->update($request->all());

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param FfCategory $ff_category
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(FfCategory $ff_category): RedirectResponse
  {
    $ff_category->deleted_by = Auth::id();
    $ff_category->save();
    $ff_category->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }
}
